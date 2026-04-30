age = 34
height_in = 70
weight_kg = 70.5
heart_rate_bpm = 72
blood_pressure_systolic = 118
blood_pressure_diastolic = 76
bodytemperature = 100

bmi = 703 * weight_kg / height_in**2
if bmi < 18.5:
    bmihealth = "Underweight"
elif bmi < 25:
    bmihealth = "Normal"
elif bmi < 30:
    bmihealth = "Overweight"
else:
    bmihealth = "Obese"

maxhr = 220 - age
if age >= 6 and age <= 15:
    if heart_rate_bpm >= 70 and heart_rate_bpm <= 100:
        heartratehealth = "Normal"
    else:
        heartratehealth = "Abnormal"
elif age >= 16 and age <= 65:
    if heart_rate_bpm >= 60 and heart_rate_bpm <= 100:
        heartratehealth = "Normal"
    else:
        heartratehealth = "Abnormal"
elif age > 65:
    if heart_rate_bpm >= 60 and heart_rate_bpm <= 100:
        heartratehealth = "Normal"
    else:
        heartratehealth = "Abnormal"
else:
    heartratehealth = "Null"

if blood_pressure_systolic < 90 and blood_pressure_diastolic < 60:
    bloodpressurehealth = "Hypotension"
elif blood_pressure_systolic < 120 and blood_pressure_diastolic < 80:
    bloodpressurehealth = "Normal"
elif blood_pressure_systolic < 130 and blood_pressure_diastolic < 80:
    bloodpressurehealth = "Elevated"
elif blood_pressure_systolic < 140 and blood_pressure_diastolic < 90:
    bloodpressurehealth = "Hypertension Stage 1"
elif blood_pressure_systolic >= 140 and blood_pressure_diastolic >= 90:
    bloodpressurehealth = "Hypertension Stage 2"
else:
    bloodpressurehealth = "Null"

if bodytemperature < 95:
    bodytemperaturehealth = "Hypothermia"
elif bodytemperature >= 95 and bodytemperature <= 100.3:
    bodytemperaturehealth = "Normal"
elif bodytemperature >= 100.4 and bodytemperature < 103:
    bodytemperaturehealth = "Fever"
else:
    bodytemperaturehealth = "High Fever"

singlevitalconditions = []
if heart_rate_bpm > 100:
    singlevitalconditions.append("Tachycardia")

if heart_rate_bpm < 60:
    singlevitalconditions.append("Bradycardia")

if bodytemperature >= 100.4:
    singlevitalconditions.append("Fever")

if bodytemperature < 95:
    singlevitalconditions.append("Hypothermia")

if blood_pressure_systolic >= 130 or blood_pressure_diastolic >= 80:
    singlevitalconditions.append("Hypertension")

if blood_pressure_systolic < 90 or blood_pressure_diastolic < 60:
    singlevitalconditions.append("Hypotension")

multivitalconditions = []
if heart_rate_bpm > 100 and blood_pressure_systolic < 90 and blood_pressure_diastolic < 60:
    multivitalconditions.append("Dehydration")

if bodytemperature >= 100.4 and heart_rate_bpm > 100:
    multivitalconditions.append("Possible Infection")

if blood_pressure_systolic >= 130 and blood_pressure_diastolic >= 80 and heart_rate_bpm > 100:
    multivitalconditions.append("Cardiovascular Stress")

combinedriskconditions = []
if bmi >= 30 and blood_pressure_systolic >= 130 and blood_pressure_diastolic >= 80 and heart_rate_bpm > 100:
    combinedriskconditions.append("Cardiovascular Risk")

if bmi < 18.5 and blood_pressure_systolic < 90 and blood_pressure_diastolic < 60:
    combinedriskconditions.append("Underweight + Hypotension")

if age >= 60 and (bmihealth != "Normal" or heartratehealth != "Normal" or bloodpressurehealth != "Normal" or bodytemperaturehealth != "Normal"):
    combinedriskconditions.append("Age-Based Risk Escalation")
