from django.db import models
from django.conf import settings

class Score(models.Model):
    user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE, related_name="scores")
    composite_score = models.PositiveIntegerField()
    notes = models.CharField(max_length=500, blank=True)
    created_at = models.DateTimeField(auto_now_add=True)

    class Meta:
        ordering = ["-composite_score", "-created_at"]

    def __str__(self) -> str:
        return f"{self.user} - {self.composite_score}"
