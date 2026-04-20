<?php
require_once __DIR__ . '/helpers.php';
require_login();
$submitCpus = bench_hardware_cpus();
$submitGpus = bench_hardware_gpus();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Benchmark Hub — Submit Score</title>
  <link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="benchmark-hub.css">
</head>
<body>
<div class="page" id="page-submit">
  <?php render_header('submit-score'); ?>
  <?php render_flash(); ?>
  <div class="content-wrap">
    <form method="post" action="save-submission.php">
      <input type="hidden" id="hidden_composite_score" name="composite_score" value="0">
      <div style="display:grid; grid-template-columns:1fr 320px; gap:24px;">
        <div>
          <div class="card" style="margin-bottom:16px;">
            <div class="card-title">System specifications</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:16px;">Choose a suggestion or type your exact model (as shown in Basemark, 3DMark, Novabench, etc.).</p>
            <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
              <div class="form-field">
                <label for="submit_cpu">CPU</label>
                <input type="text" id="submit_cpu" name="cpu" list="cpu_suggestions" required autocomplete="off" maxlength="160" placeholder="e.g. Intel Core i7-13700K">
                <datalist id="cpu_suggestions"><?php foreach ($submitCpus as $cpu): ?><option value="<?= e($cpu) ?>"></option><?php endforeach; ?></datalist>
              </div>
              <div class="form-field">
                <label for="submit_gpu">GPU</label>
                <input type="text" id="submit_gpu" name="gpu" list="gpu_suggestions" required autocomplete="off" maxlength="160" placeholder="e.g. NVIDIA GeForce RTX 4070">
                <datalist id="gpu_suggestions"><?php foreach ($submitGpus as $gpu): ?><option value="<?= e($gpu) ?>"></option><?php endforeach; ?></datalist>
              </div>
            </div>
          </div>

          <div class="card" style="margin-bottom:16px;">
            <div class="card-title">Enter Benchmark Scores</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">Enter scores for benchmarks you’ve run.</p>
            <div class="benchmark-input-grid">
              <?php foreach (benchmark_labels() as $key => $label): ?>
                <div class="benchmark-card">
                  <div class="bname"><span class="dot"></span><?= e($label) ?></div>
                  <div class="form-field">
                    <label for="score_<?= e($key) ?>">Overall Score</label>
                    <input id="score_<?= e($key) ?>" name="<?= e($key) ?>" type="number" min="0" placeholder="e.g. 8000" class="score-input" data-target="<?= e($key) ?>">
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div class="card">
            <div class="card-title">Optional — Submission Notes</div>
            <div class="form-field">
              <label for="submission_notes">How did you achieve this score? (Optional)</label>
              <textarea id="submission_notes" name="submission_notes" rows="3" maxlength="100" placeholder="e.g. Liquid cooling, XMP enabled, GPU OC..."></textarea>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
          <div class="total-score-display">
            <div class="label">Composite score (preview)</div>
            <div class="value" id="composite_preview">0</div>
            <div class="sub">For display only until submit. Average of entered scores.</div>
          </div>

          <div class="card">
            <div class="card-title">Score Breakdown</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
              <?php foreach (benchmark_labels() as $key => $label): ?>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;"><span><?= e($label) ?></span><span class="score-val" id="val-<?= e($key) ?>" style="font-size:13px;">0</span></div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-<?= e($key) ?>" style="width:0%"></div></div>
              </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="submit" class="btn btn-primary" style="padding:12px; width:100%; cursor:pointer; border:none; font:inherit;">Submit score</button>
            <a href="leaderboard.php" class="btn btn-ghost" style="padding:12px; text-align:center; text-decoration:none;">View leaderboard</a>
            <a href="dashboard.php" class="btn btn-ghost" style="padding:12px; text-align:center; text-decoration:none;">← Back to dashboard</a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('.score-input');
  const compositePreview = document.getElementById('composite_preview');
  const hiddenComposite = document.getElementById('hidden_composite_score');
  const MAX_VAL = 20000;
  function recalc() {
    let total = 0;
    let count = 0;
    inputs.forEach((input) => {
      const target = input.dataset.target;
      const val = parseInt(input.value, 10) || 0;
      document.getElementById(`val-${target}`).textContent = val.toLocaleString();
      document.getElementById(`bar-${target}`).style.width = Math.min((val / MAX_VAL) * 100, 100) + '%';
      if (val > 0) { total += val; count += 1; }
    });
    const composite = count > 0 ? Math.round(total / count) : 0;
    compositePreview.textContent = composite.toLocaleString();
    hiddenComposite.value = composite;
  }
  inputs.forEach((input) => input.addEventListener('input', recalc));
  recalc();
});
</script>
</body>
</html>
