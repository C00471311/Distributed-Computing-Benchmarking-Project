# Benchmarking Application

## Rough Idea

An application that focuses on compiling multiple popular benchmarking software from throughout the internet. For reference, a benchmark is a program that pushes the components of a computer to the maximum in order to test a component’s processing power.

## The Problem

Basing computing power on one benchmark can sometimes be inaccurate. A simple change of a setting or accidental click can cause performance issues, leading to a significantly dropped computing score (e.g., changing the resolution of a GPU benchmark without realizing it).

Additionally, many people online aim to prove who has the strongest system. Although being at the top of the leaderboard of a single benchmark may be appealing, being at the top of a leaderboard for many benchmarks is much more meaningful.

## The Solution

The application will allow users to input their scores from multiple popular benchmarks. These benchmarks run on a user’s PC to measure performance on specific tasks (for example, rendering a demanding 3D scene using CPU and GPU resources). Scores are generated based on how quickly tasks are completed.

Users can store all their benchmark scores in one place rather than being confused by multiple scoring systems.

This alone does not fully solve benchmarking inaccuracies, but with filtering tools users can compare their scores with others who have similar computer specifications.

By comparing grouped scores:

- If only one benchmark score is unusually low → likely a setup/configuration issue
- If all benchmark scores are unusually low → possible hardware or system performance problem

## Features

### Unified Scoring Algorithm
An algorithm that creates a single combined score using five benchmark results. The scores are standardized and aggregated into one value used for leaderboard ranking.

### Filtering System
Filters may include:

- Highest / Lowest Score
- CPU-based comparisons
- GPU-based comparisons
- Scores similar to your own
- Combined filter options

These filters are useful because some benchmarks target specific hardware components, allowing more meaningful comparisons than full-system benchmarks alone.

### User Comment System
Users can leave comments explaining how they achieved their score.

This helps others understand performance differences.

**Example:**
> “I used liquid cooling with an overclock of 5.2GHz on all P-cores.”

## Technical Strategy

- Web server architecture
- Multiple web servers handling different responsibilities and communicating with each other
- A centralized database server containing multiple tables
- Interactive web application interface
- User role system with special administrators responsible for validating benchmark submissions

## Technical Stack Selection and Assignees

**Cloud Infrastructure**
- AWS (or a hosting provider built on AWS services)

**Frontend Development**
- Akriti Sharma
- Ichhya Amatya

**Database & User Access Control**
- Justin Le
- Jacob Gilbert

**Core Systems & Algorithms**
- Gregory Thibodeaux
- Ky Smith

## Deliverables

- Frontend web application
  - Leaderboard
  - Score submissions
  - User profiles
  - Detailed score pages
- Backend services
  - Authentication & user access control
  - Score calculation algorithm
  - Score validation system
  - Leaderboard management
- Admin validation system for approving leaderboard entries
- Cloud deployment using AWS

## Milestones

**Planning Phase (Weeks 1–2)**
- Project planning and system design

**Role Definitions (Weeks 3–4)**
- Assigning responsibilities and organizing workflow

**Backend & Frontend Development (Weeks 5–8)**
- Database implementation
- Feature development
- Frontend interface creation  
*(These tasks can be developed in parallel)*

**AWS Infrastructure Integration (Weeks 9–10)**
- Connecting servers and deploying services

**Testing & Polishing (Weeks 10–11)**
- System testing
- Bug fixes
- Performance optimization
- UI improvements