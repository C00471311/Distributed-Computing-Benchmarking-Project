from django.conf import settings
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


def wearable_index(request: HttpRequest) -> HttpResponse:
    path = settings.BASE_DIR / "wearable" / "index.html"
    return HttpResponse(
        path.read_text(encoding="utf-8"),
        content_type="text/html; charset=utf-8",
    )
