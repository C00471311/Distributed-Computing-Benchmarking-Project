<?php require_once __DIR__ . '/helpers.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Benchmark Hub — Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="benchmark-hub.css">
</head>
<body>
<div class="page" id="page-dashboard">
  <?php render_header('dashboard'); ?>
  <?php render_flash(); ?>
  <div class="content-wrap">
    <div style="margin-top:8px; margin-bottom:28px;">
      <h1 style="font-size:26px; font-weight:700; font-family:'Courier Prime',monospace;">Dashboard</h1>
      <p style="color:var(--text-muted); font-size:14px; margin-top:6px; max-width:560px;">Welcome back, <?= e(current_user_name()) ?>. Use the same frontend flow to submit scores and review rankings.</p>
    </div>
    <div class="two-col">
      <a href="submit-score.php" class="card" style="text-decoration:none; color:inherit; display:block; transition: border-color 0.15s;">
        <div class="card-title">Submit score</div>
        <p style="font-size:14px; color:var(--text-muted); line-height:1.5;">Open the benchmark entry flow and send your submission to the review queue.</p>
        <span class="btn btn-primary" style="margin-top:16px; display:inline-block;">Continue →</span>
      </a>
      <a href="leaderboard.php" class="card" style="text-decoration:none; color:inherit; display:block;">
        <div class="card-title">Leaderboard</div>
        <p style="font-size:14px; color:var(--text-muted); line-height:1.5;">Browse approved benchmark scores.</p>
        <span class="btn" style="margin-top:16px; display:inline-block;">View →</span>
      </a>
    </div>
  </div>
</div>
</body>
</html>
