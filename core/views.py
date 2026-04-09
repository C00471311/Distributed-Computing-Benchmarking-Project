from django.contrib import messages
from django.contrib.auth import authenticate, login, logout
from django.contrib.auth.decorators import login_required
from django.contrib.auth.models import User
from django.http import HttpRequest, HttpResponse
from django.shortcuts import redirect, render
from django.urls import reverse

from .models import Score

def index(request: HttpRequest) -> HttpResponse:
    if request.user.is_authenticated:
        return redirect("leaderboard")
    return redirect("login")


def login_view(request: HttpRequest) -> HttpResponse:
    if request.user.is_authenticated:
        return redirect("submit_score")

    if request.method == "POST":
        username = (request.POST.get("username") or "").strip()
        password = request.POST.get("password") or ""

        user = authenticate(request, username=username, password=password)
        if user is None:
            messages.error(request, "Invalid username or password.")
            return render(request, "login.html", status=401)

        login(request, user)
        return redirect("submit_score")

    return render(request, "login.html")


def logout_view(request: HttpRequest) -> HttpResponse:
    logout(request)
    return redirect("login")


@login_required(login_url="login")
def submit_score(request: HttpRequest) -> HttpResponse:
    if request.method == "POST":
        raw_score = (request.POST.get("composite_score") or "").strip()
        notes = (request.POST.get("notes") or "").strip()

        try:
            composite_score = int(raw_score)
        except ValueError:
            messages.error(request, "Please enter a valid number for your composite score.")
            return render(request, "submit-score.html", status=400)

        if composite_score <= 0:
            messages.error(request, "Composite score must be a positive number.")
            return render(request, "submit-score.html", status=400)

        Score.objects.create(user=request.user, composite_score=composite_score, notes=notes[:500])
        messages.success(request, "Score submitted!")
        return redirect("leaderboard")

    return render(request, "submit-score.html")


def leaderboard(request: HttpRequest) -> HttpResponse:
    top_scores = (
        Score.objects.select_related("user")
        .order_by("-composite_score", "-created_at")[:50]
    )
    return render(request, "leaderboard.html", {"scores": top_scores})
