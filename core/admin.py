from django.contrib import admin
from .models import Score

@admin.register(Score)
class ScoreAdmin(admin.ModelAdmin):
    list_display = ("id", "user", "composite_score", "created_at")
    list_select_related = ("user",)
    search_fields = ("user__username", "user__email")
    ordering = ("-composite_score", "-created_at")
