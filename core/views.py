from django.http import HttpRequest, HttpResponse
from django.shortcuts import redirect, render


def index(request: HttpRequest) -> HttpResponse:
    return redirect("login")


def login_view(request: HttpRequest) -> HttpResponse:
    return render(request, "login.html")


def dashboard(request: HttpRequest) -> HttpResponse:
    return render(request, "dashboard.html")


def submit_score(request: HttpRequest) -> HttpResponse:
    return render(request, "submit-score.html")


def leaderboard(request: HttpRequest) -> HttpResponse:
    return render(request, "leaderboard.html")
