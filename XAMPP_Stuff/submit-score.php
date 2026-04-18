<?php require_once __DIR__ . '/helpers.php'; ?>
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

  <div class="content-wrap">
    <!-- <div class="steps" style="margin-top:20px;">
      <div class="step done">
        <div class="step-num">✓</div>
        <span>System Specs</span>
      </div>
      <div class="step-line"></div>
      <div class="step active">
        <div class="step-num">2</div>
        <span>Benchmark Scores</span>
      </div>
      <div class="step-line"></div>
      <div class="step">
        <div class="step-num">3</div>
        <span>Review &amp; Submit</span>
      </div>
    </div> -->

    <form method="post" action="save-submission.php">
      <div style="display:grid; grid-template-columns:1fr 320px; gap:24px;">
        <div>
          <div class="card" style="margin-bottom:16px;">
            <div class="card-title">Enter Benchmark Scores</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">Enter scores for benchmarks you’ve run (UI only — nothing is saved yet).</p>

            <div class="benchmark-input-grid">
              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>Basemark</div>
                <div class="form-field">
                  <label for="score_basemark">Overall Score</label>
                  <input id="score_basemark" name="basemark" type="number" placeholder="e.g. 8000" class="score-input" data-target="basemark">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>3DMark</div>
                <div class="form-field">
                  <label for="score_3dmark">Overall Score</label>
                  <input id="score_3dmark" name="3dmark" type="number" placeholder="e.g. 12000" class="score-input" data-target="3dmark">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>Novabench</div>
                <div class="form-field">
                  <label for="score_novabench">Overall Score</label>
                  <input id="score_novabench" name="novabench" type="number" placeholder="e.g. 4000" class="score-input" data-target="novabench">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>BAPco SYSmark</div>
                <div class="form-field">
                  <label for="score_sysmark">Overall Score</label>
                  <input id="score_sysmark" name="sysmark" type="number" placeholder="e.g. 2500" class="score-input" data-target="sysmark">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>Passmark</div>
                <div class="form-field">
                  <label for="score_passmark">Overall Score</label>
                  <input id="score_passmark" name="passmark" type="number" placeholder="e.g. 9000" class="score-input" data-target="passmark">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-title">Optional — Submission Notes</div>
            <div class="form-field">
              <label for="submission_notes">How did you achieve this score? (Optional)</label>
              <textarea id="submission_notes" name="submission_notes" rows="3" maxlength="500" placeholder="e.g. Liquid cooling, XMP enabled, GPU OC..."></textarea>
            </div>
          </div>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px;">
          <div class="total-score-display">
            <div class="label">Composite score (preview)</div>
            <div class="form-field" style="align-items:center;">
              <label for="id_composite_score" class="sr-only" style="position:absolute;width:1px;height:1px;overflow:hidden;">Composite</label>
              <input id="id_composite_score" type="number" placeholder="e.g. 68430" style="max-width:220px; text-align:center; font-family:'Courier Prime',monospace; font-size:28px; font-weight:700; padding:12px;">
            </div>
            <div class="sub">For display only until backend wiring is added.</div>
          </div>

          <div class="card">
            <div class="card-title">Score Breakdown</div>
            <div style="display:flex;flex-direction:column;gap:12px;">
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>Basemark</span><span class="score-val" id="val-basemark" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-basemark" style="width:0%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>3DMark</span><span class="score-val" id="val-3dmark" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-3dmark" style="width:0%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>Novabench</span><span class="score-val" id="val-novabench" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-novabench" style="width:0%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>SYSmark</span><span class="score-val" id="val-sysmark" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-sysmark" style="width:0%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>Passmark</span><span class="score-val" id="val-passmark" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" id="bar-passmark" style="width:0%"></div></div>
              </div>
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:8px;">
            <a href="leaderboard.php"  class="btn btn-primary" style="padding:12px; text-align:center; text-decoration:none; text-align:center;">
              Next: Leaderboard →
            </a>
            <a href="dashboard.php" class="btn btn-ghost" style="padding:12px; text-align:center; text-decoration:none;">
              ← Back to dashboard
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const inputs = document.querySelectorAll('.score-input');
  // Arbitrary max value for the progress bar visualization
  const MAX_VAL = 15000;

  inputs.forEach(input => {
    input.addEventListener('input', (e) => {
      const target = e.target.dataset.target;
      const val = parseInt(e.target.value) || 0;
      
      document.getElementById(`val-${target}`).textContent = val.toLocaleString();
      const percent = Math.min(Math.max((val / MAX_VAL) * 100, 0), 100);
      document.getElementById(`bar-${target}`).style.width = percent + '%';
    });
  });
});
</script>
</body>
</html>