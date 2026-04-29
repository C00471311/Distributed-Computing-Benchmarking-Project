
# Vitals Research Data

## Health Monitoring System

Vital signs, threshold ranges, and rule-based conditions used in a wearable health monitoring system.  
The system processes user-input (simulated) data and generates alerts and recommendations based on predefined thresholds.

This version incorporates **age, height, and weight** to improve accuracy and contextualize health risk.

---

## 2. Monitored Inputs

The system collects the following user data:

- Age  
- Height (inches)  
- Weight (pounds)  
- Heart Rate (BPM)  
- Blood Pressure (Systolic / Diastolic, mmHg)  
- Body Temperature (°F)  

---


## 3. Derived Metrics

### 3.1 Body Mass Index (BMI)

BMI is calculated using height and weight:

BMI = 703 × weight / height(in)^2


| Category     | BMI Range      |
|--------------|---------------|
| Underweight  | < 18.5        |
| Normal       | 18.5 – 24.9   |
| Overweight   | 25 – 29.9     |
| Obese        | ≥ 30          |

---

### 3.2 Maximum Heart Rate (Age-Based)
MaxHR = 220 − age

Used to determine if heart rate is abnormally high relative to age.

---


## 4. Standard Vital Ranges

### 4.1 Heart Rate (Adjusted by Age)

| Age Group | Normal Resting HR |
|----------|------------------|
| 6–15     | 70–100           |
| 16–65    | 60–100           |
| 65+      | 60–100 (monitor closely) |

---

### 4.2 Blood Pressure

| Category                  | Systolic / Diastolic (mmHg) |
|---------------------------|-----------------------------|
| Normal                    | <120 / <80                  |
| Elevated                  | 120–129 / <80               |
| Hypertension Stage 1      | 130–139 / 80–89             |
| Hypertension Stage 2      | ≥140 / ≥90                  |
| Hypotension               | <90 / <60                   |

---

### 4.3 Body Temperature

| Category     | Temperature (°F) |
|--------------|------------------|
| Normal       | 97–99            |
| Fever        | ≥100.4           |
| High Fever   | ≥103             |
| Hypothermia  | <95              |

---

## 5. Condition Detection Rules

### 5.1 Single-Vital Conditions

#### Tachycardia
- **Trigger:** Heart Rate > 100 BPM  
- **Recommendations:**
  - Rest and sit down  
  - Drink fluids  
  - Avoid caffeine  

---

#### Bradycardia
- **Trigger:** Heart Rate < 60 BPM  
- **Recommendations:**
  - Monitor for dizziness or fatigue  
  - Seek care if symptoms worsen  

---

#### Fever
- **Trigger:** Temperature ≥ 100.4°F  
- **Recommendations:**
  - Rest  
  - Stay hydrated  
  - Use fever-reducing medication  

---

#### Hypothermia
- **Trigger:** Temperature < 95°F  
- **Recommendations:**
  - Warm up immediately  
  - Use blankets or warm fluids  
  - Seek medical attention  

---

#### Hypertension
- **Trigger:** Systolic ≥130 OR Diastolic ≥80  
- **Recommendations:**
  - Reduce sodium intake  
  - Manage stress  
  - Exercise regularly  

---

#### Hypotension
- **Trigger:** Systolic <90 OR Diastolic <60  
- **Recommendations:**
  - Drink fluids  
  - Sit or lie down  
  - Stand slowly  

---

## 6. Multi-Vital Condition Detection

### 6.1 Dehydration
- **Trigger Conditions:**
  - Heart Rate > 100  
  - Blood Pressure < 90/60  
- **Recommendations:**
  - Drink water or electrolytes  
  - Rest  

---

### 6.2 Possible Infection
- **Trigger Conditions:**
  - Fever ≥100.4°F  
  - Heart Rate >100 BPM  
- **Recommendations:**
  - Rest  
  - Hydration  
  - Monitor symptoms  

---

### 6.3 Cardiovascular Stress
- **Trigger Conditions:**
  - Blood Pressure ≥130/80  
  - Heart Rate >100 BPM  
- **Recommendations:**
  - Reduce physical activity  
  - Practice relaxation techniques  

---

## 7. Age & Body Metric Integration

Age, height, and weight adjust risk levels rather than acting as direct triggers.

---

### 7.1 Age-Based Adjustments

| Rule                          | Effect |
|-------------------------------|--------|
| Age ≥ 60 + abnormal vital     | Increase severity |
| Age < 16                      | Higher HR baseline |
| HR > 85% of MaxHR             | Flag abnormal |

---

### 7.2 BMI-Based Conditions

#### Underweight
- **Trigger:** BMI < 18.5  
- **Risks:**
  - Fatigue  
  - Weak immune response  
- **Recommendations:**
  - Increase calorie intake  
  - Improve nutrition  

---

#### Overweight / Obese
- **Trigger:** BMI ≥ 25  
- **Risks:**
  - Elevated blood pressure  
  - Increased heart strain  
- **Recommendations:**
  - Regular exercise  
  - Balanced diet  

---

### 7.3 Combined Risk Conditions

#### Cardiovascular Risk
- **Trigger:**
  - BMI ≥ 30  
  - Blood Pressure ≥130/80  
  - Heart Rate > 100  
- **Interpretation:** Elevated long-term cardiovascular risk  

---

#### Underweight + Hypotension
- **Trigger:**
  - BMI < 18.5  
  - Blood Pressure < 90/60  
- **Interpretation:** Possible weakness or poor circulation  

---

#### Age-Based Risk Escalation
- **Trigger:**
  - Age ≥ 60  
  - Any abnormal vital  
- **Effect:** Increase alert severity  

---

## 8. Advanced Condition

### Postural Tachycardia-like Detection (POTS Simulation)

**Trigger Conditions:**
- Heart Rate > 100 BPM  
- Increase ≥ 30 BPM from baseline  
- Normal or low blood pressure  

**Age Consideration:**
- More common under age 40  

**Recommendations:**
- Sit or lie down immediately  
- Increase fluid intake  
- Avoid sudden standing  

---

## 9. Notes

- Uses threshold-based logic in backend services (AWS Lambda)  
- BMI modifies risk evaluation  
- Age adjusts thresholds and severity  
- Conditions use simple logical comparisons  
- Data can be stored in DynamoDB and returned as alerts  
## 5. Condition Detection Rules

### 5.1 Single-Vital Conditions

#### Tachycardia
- **Trigger:** Heart Rate > 100 BPM  
- **Recommendations:**
  - Rest and sit down  
  - Drink fluids  
  - Avoid caffeine  

---

#### Bradycardia
- **Trigger:** Heart Rate < 60 BPM  
- **Recommendations:**
  - Monitor for dizziness or fatigue  
  - Seek care if symptoms worsen  

---

#### Fever
- **Trigger:** Temperature ≥ 100.4°F  
- **Recommendations:**
  - Rest  
  - Stay hydrated  
  - Use fever-reducing medication  

---

#### Hypothermia
- **Trigger:** Temperature < 95°F  
- **Recommendations:**
  - Warm up immediately  
  - Use blankets or warm fluids  
  - Seek medical attention  

---

#### Hypertension
- **Trigger:** Systolic ≥130 OR Diastolic ≥80  
- **Recommendations:**
  - Reduce sodium intake  
  - Manage stress  
  - Exercise regularly  

---

#### Hypotension
- **Trigger:** Systolic <90 OR Diastolic <60  
- **Recommendations:**
  - Drink fluids  
  - Sit or lie down  
  - Stand slowly  

---


## 7. Age & Body Metric Integration

Age, height, and weight adjust risk levels rather than acting as direct triggers.

---
## 6. Multi-Vital Condition Detection

### 6.1 Dehydration
- **Trigger Conditions:**
  - Heart Rate > 100  
  - Blood Pressure < 90/60  
- **Recommendations:**
  - Drink water or electrolytes  
  - Rest  

---

### 6.2 Possible Infection
- **Trigger Conditions:**
  - Fever ≥100.4°F  
  - Heart Rate >100 BPM  
- **Recommendations:**
  - Rest  
  - Hydration  
  - Monitor symptoms  

---

### 6.3 Cardiovascular Stress
- **Trigger Conditions:**
  - Blood Pressure ≥130/80  
  - Heart Rate >100 BPM  
- **Recommendations:**
  - Reduce physical activity  
  - Practice relaxation techniques  

---

## Age & Body Metric Integration + Additional Conditions

Age, height, and weight adjust risk levels rather than acting as direct triggers.

---

### 7.1 Age-Based Adjustments

| Rule                          | Effect |
|-------------------------------|--------|
| Age ≥ 60 + abnormal vital     | Increase severity |
| Age < 16                      | Higher HR baseline |
| HR > 85% of MaxHR             | Flag abnormal |

---

### 7.2 BMI-Based Conditions

#### Underweight
- **Trigger:** BMI < 18.5  
- **Risks:**
  - Fatigue  
  - Weak immune response  
- **Recommendations:**
  - Increase calorie intake  
  - Improve nutrition  

---

#### Overweight / Obese
- **Trigger:** BMI ≥ 25  
- **Risks:**
  - Elevated blood pressure  
  - Increased heart strain  
- **Recommendations:**
  - Regular exercise  
  - Balanced diet  

---

### 7.3 Combined Risk Conditions

#### Cardiovascular Risk
- **Trigger:**
  - BMI ≥ 30  
  - Blood Pressure ≥130/80  
  - Heart Rate > 100  
- **Interpretation:** Elevated long-term cardiovascular risk  

---

#### Underweight + Hypotension
- **Trigger:**
  - BMI < 18.5  
  - Blood Pressure < 90/60  
- **Interpretation:** Possible weakness or poor circulation  

---

#### Age-Based Risk Escalation
- **Trigger:**
  - Age ≥ 60  
  - Any abnormal vital  
- **Effect:** Increase alert severity  

---

## 8. Advanced Condition

### Postural Tachycardia-like Detection (POTS Simulation)

**Trigger Conditions:**
- Heart Rate > 100 BPM  
- Increase ≥ 30 BPM from baseline  
- Normal or low blood pressure  

**Age Consideration:**
- More common under age 40  

**Recommendations:**
- Sit or lie down immediately  
- Increase fluid intake  
- Avoid sudden standing  

---
## 9. Notes

- Uses threshold-based logic in backend services (AWS Lambda)  
- BMI modifies risk evaluation  
- Age adjusts thresholds and severity  
- Conditions use simple logical comparisons  
- Data can be stored in DynamoDB and returned as alerts  
