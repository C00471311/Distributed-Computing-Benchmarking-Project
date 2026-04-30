import json
import time

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

    # For Single COndition
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

    # For Multi conditions
    if hr > 100 and sys < 90:
        alerts.append("Dehydration")

    if temp >= 100.4 and hr > 100:
        alerts.append("Possible Infection")

    if sys >= 130 and hr > 100:
        alerts.append("Cardiovascular Stress")

    # For BMI conditions
    if bmi >= 30:
        alerts.append("Obese")

    if bmi < 18.5:
        alerts.append("Underweight")

    return alerts, recommendations


def lambda_handler(event, context):
    try:
        # Handling API Gateway
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

        return {
            "statusCode": 200,
            "body": json.dumps({
                "bmi": round(bmi, 2),
                "alerts": alerts,
                "recommendations": recommendations,
                "severity": severity
            })
        }

    except Exception as e:
        return {
            "statusCode": 500,
            "body": json.dumps({"error": str(e)})
        }