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
    <div class="steps" style="margin-top:20px;">
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
    </div>

    <form method="post" action="save-submission.php">
      <div style="display:grid; grid-template-columns:1fr 320px; gap:24px;">
        <div>
          <div class="card" style="margin-bottom:16px;">
            <div class="card-title">Enter Benchmark Scores</div>
            <p style="font-size:13px; color:var(--text-muted); margin-bottom:20px;">Enter scores for benchmarks you’ve run (UI only — nothing is saved yet).</p>

            <div class="benchmark-input-grid">
              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>Cinebench R23</div>
                <div class="form-field">
                  <label for="cinebench_multi">Multi-Core Score</label>
                  <input id="cinebench_multi" name="cinebench_multi" type="number" placeholder="e.g. 28400">
                </div>
                <div class="form-field" style="margin-top:10px;">
                  <label for="cinebench_single">Single-Core Score</label>
                  <input id="cinebench_single" name="cinebench_single" type="number" placeholder="e.g. 1950">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>3DMark (TimeSpy)</div>
                <div class="form-field">
                  <label for="timespy_overall">Overall Score</label>
                  <input id="timespy_overall" name="timespy_overall" type="number" placeholder="e.g. 17200">
                </div>
                <div class="form-field" style="margin-top:10px;">
                  <label for="timespy_graphics">Graphics Score</label>
                  <input id="timespy_graphics" name="timespy_graphics" type="number" placeholder="e.g. 18900">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>PCMark 10</div>
                <div class="form-field">
                  <label for="pcmark10_overall">Overall Score</label>
                  <input id="pcmark10_overall" name="pcmark10_overall" type="number" placeholder="e.g. 8100">
                </div>
              </div>

              <div class="benchmark-card">
                <div class="bname"><span class="dot"></span>Blender (BMW Scene)</div>
                <div class="form-field">
                  <label for="blender_render_time">Render Time (seconds)</label>
                  <input id="blender_render_time" name="blender_render_time" type="number" placeholder="e.g. 245">
                </div>
              </div>

              <div class="benchmark-card" style="grid-column:1/-1;">
                <div class="bname"><span class="dot"></span>Heaven Benchmark 4.0</div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr; gap:12px;">
                  <div class="form-field">
                    <label for="heaven_score">Score</label>
                    <input id="heaven_score" name="heaven_score" type="number" placeholder="e.g. 3240">
                  </div>
                  <div class="form-field">
                    <label for="heaven_fps_avg">FPS (avg)</label>
                    <input id="heaven_fps_avg" name="heaven_fps_avg" type="number" placeholder="e.g. 128">
                  </div>
                  <div class="form-field">
                    <label for="heaven_preset">Preset Used</label>
                    <select id="heaven_preset" name="heaven_preset">
                      <option>Ultra</option>
                      <option>Extreme</option>
                      <option>High</option>
                    </select>
                  </div>
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
                  <span>Cinebench R23</span><span class="score-val" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" style="width:72%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>3DMark</span><span class="score-val" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" style="width:60%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>PCMark 10</span><span class="score-val" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" style="width:55%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>Blender</span><span class="score-val" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" style="width:48%"></div></div>
              </div>
              <div>
                <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px;">
                  <span>Heaven</span><span class="score-val" style="font-size:13px;">0</span>
                </div>
                <div class="score-bar-track"><div class="score-bar-fill" style="width:65%"></div></div>
              </div>
            </div>
          </div>

          <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="submit" class="btn btn-primary" style="padding:12px; text-align:center;">
              Next: Leaderboard →
            </button>
            <a href="dashboard.php" class="btn btn-ghost" style="padding:12px; text-align:center; text-decoration:none;">
              ← Back to dashboard
            </a>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</body>
</html>