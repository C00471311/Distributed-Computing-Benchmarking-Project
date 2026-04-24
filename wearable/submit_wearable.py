import sys

import requests

# REST API URL (replace with API Gateway URL)
API_URL = "https://myURL.execute-api.us-west-2.amazonaws.com/prod"

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
    if "myURL" in API_URL:
        print("Edit API_URL in submit_wearable.py to your real endpoint.", file=sys.stderr)
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
