from django.urls import path

from . import views

urlpatterns = [
    path("", views.index, name="index"),
    path("login/", views.login_view, name="login"),
    path("dashboard/", views.dashboard, name="dashboard"),
    path("submit-score/", views.submit_score, name="submit_score"),
    path("leaderboard/", views.leaderboard, name="leaderboard"),
]

