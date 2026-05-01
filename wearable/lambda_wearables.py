import json
import boto3
import uuid
from decimal import Decimal
from datetime import datetime, timezone

dynamodb = boto3.resource("dynamodb")
table = dynamodb.Table("HealthRecords")


def calculate_bmi(weight, height):
    return (703 * weight) / (height ** 2)


def detect_conditions(data, bmi):
    alerts = []
    recommendations = []

    hr = data["heart_rate"]
    sys = data["systolic"]
    dia = data["diastolic"]
    temp = data["temperature"]
    age = data["age"]

    # Single conditions
    if hr > 100:
        alerts.append("Tachycardia")
        recommendations.append("Rest and hydrate")
    if hr < 60:
        alerts.append("Bradycardia")
    if temp >= 100.4:
        alerts.append("Fever")
        recommendations.append("Take rest and fluids")
    if temp < 95:
        alerts.append("Hypothermia")
    if sys >= 130 or dia >= 80:
        alerts.append("Hypertension")
    if sys < 90 or dia < 60:
        alerts.append("Hypotension")

    # Multi conditions
    if hr > 100 and sys < 90:
        alerts.append("Dehydration")
    if temp >= 100.4 and hr > 100:
        alerts.append("Possible Infection")
    if sys >= 130 and hr > 100:
        alerts.append("Cardiovascular Stress")

    # BMI conditions
    if bmi >= 30:
        alerts.append("Obese")
    if bmi < 18.5:
        alerts.append("Underweight")

    return alerts, recommendations


def lambda_handler(event, context):
    try:
        # CORS preflight (HTTP API auto-handles, but harmless to keep)
        if event.get("httpMethod") == "OPTIONS":
            return {
                "statusCode": 200,
                "headers": {
                    "Access-Control-Allow-Origin": "*",
                    "Access-Control-Allow-Headers": "Content-Type",
                    "Access-Control-Allow-Methods": "OPTIONS,POST"
                },
                "body": ""
            }

        # Parse body
        if "body" in event:
            data = json.loads(event["body"])
        else:
            data = event

        bmi = calculate_bmi(data["weight"], data["height"])
        alerts, recommendations = detect_conditions(data, bmi)

        severity = "LOW"
        if len(alerts) >= 3:
            severity = "HIGH"
        elif len(alerts) == 2:
            severity = "MEDIUM"

        # Write to DynamoDB
        record = {
            "record_id": str(uuid.uuid4()),
            "timestamp": datetime.now(timezone.utc).isoformat(),
            "input": {
                "weight": Decimal(str(data["weight"])),
                "height": Decimal(str(data["height"])),
                "heart_rate": data["heart_rate"],
                "systolic": data["systolic"],
                "diastolic": data["diastolic"],
                "temperature": Decimal(str(data["temperature"])),
                "age": data["age"],
            },
            "bmi": Decimal(str(round(bmi, 2))),
            "alerts": alerts,
            "recommendations": recommendations,
            "severity": severity,
        }
        table.put_item(Item=record)

        return {
            "statusCode": 200,
            "headers": {
                "Access-Control-Allow-Origin": "*",
                "Access-Control-Allow-Headers": "Content-Type",
                "Access-Control-Allow-Methods": "OPTIONS,POST"
            },
            "body": json.dumps({
                "record_id": record["record_id"],
                "bmi": round(bmi, 2),
                "alerts": alerts,
                "recommendations": recommendations,
                "severity": severity
            })
        }

    except Exception as e:
        return {
            "statusCode": 500,
            "headers": {
                "Access-Control-Allow-Origin": "*"
            },
            "body": json.dumps({"error": str(e)})
        }