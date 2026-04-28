import sys

import requests

# REST API URL (API Gateway URL)
API_URL = "https://pv2o9bb53j.execute-api.us-east-2.amazonaws.com/prod/wearable"

PAYLOAD = {
    "blood_pressure_systolic": 118,
    "blood_pressure_diastolic": 76,
    "heart_rate_bpm": 72,
    "age": 34,
    "gender": "unspecified",
    "weight_kg": 70.5,
}

HEADERS = {"Content-Type": "application/json"}


def main() -> int:
    try:
        response = requests.post(API_URL, headers=HEADERS, json=PAYLOAD, timeout=30)
    except requests.RequestException as exc:
        print(f"Request failed: {exc}", file=sys.stderr)
        return 1
    print(response.status_code)
    print(response.text)
    return 0 if response.ok else 1


if __name__ == "__main__":
    raise SystemExit(main())
