"""
Scoring algorithm for the scoreboard project.
Uses scores that are inputed to do a quick calculation to output in the scoreboard on website.
Current websites that are planned to be used: Basemark, 3Dmark, Novabench, BAPco SYSmark, and Passmark
"""

def compute_scores(scores):
    total = 0
    count = 0

    for value in scores.values():
        try:
            number = max(0, float(value)) # Makes sure the number isn't negative
            total += int(number + 0.5) # Rounds the number properly since some websites (Looking at you Passmark) uses a float instead of an integer 
            count += 1
        except (ValueError, TypeError):
            continue

    return int(total / count) if count else 0

# Replace the scores as needed for testing. Can be deleted when implemeneted on the website if it's giving issues I'm not too sure how Django handles it.
if __name__ == "__main__":
    scores = {
        "basemark": 8000,
        "3dmark": 12000,
        "novabench": 4000,
        "sysmark": 2500,
        "pcbenchmarks": 9000
    }

    print("Final Score:", compute_scores(scores))