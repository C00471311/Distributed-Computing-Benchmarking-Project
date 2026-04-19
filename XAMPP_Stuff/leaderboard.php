<?php require_once __DIR__ . '/helpers.php';
$leaderboardRows = $leaderboardRows ?? [];
$filters = $filters ?? [];
$benchmarks = $benchmarks ?? ['All Benchmarks','Cinebench R23','3DMark','PCMark 10','Blender','Heaven'];
$cpus = $cpus ?? bench_leaderboard_cpu_filter_options();
$gpus = $gpus ?? bench_leaderboard_gpu_filter_options();
$sortOptions = $sortOptions ?? ['Highest Score','Lowest Score','Near My Score'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Benchmark Hub — Leaderboard</title>
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="benchmark-hub.css">
</head>
<body>
<div class="page" id="page-leaderboard">
  <?php render_header('leaderboard'); ?>

  <div class="content-wrap">
    <div style="display:flex; align-items:flex-end; justify-content:space-between; margin-top:20px; margin-bottom:20px;">
      <div>
        <h1 style="font-size:26px; font-weight:700; font-family:'Courier Prime',monospace;">Global Leaderboard</h1>
        <p style="color:var(--text-muted); font-size:14px; margin-top:4px;">Browse submitted benchmark rankings.</p>
      </div>
      <a href="submit-score.php" class="btn btn-primary" style="text-decoration:none;">+ Submit score</a>
    </div>

    <div class="card" style="padding:18px; margin-bottom:20px;">
      <div class="section-title">Filters</div>
      <form class="filter-row" method="get" action="leaderboard.php">
        <div class="form-field" style="min-width:160px;">
          <label>Benchmark</label>
          <select name="benchmark">
            <?php foreach ($benchmarks as $option): ?>
              <option value="<?= e($option) ?>" <?= (($filters['benchmark'] ?? 'All Benchmarks') === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:160px;">
          <label>CPU</label>
          <select name="cpu">
            <?php foreach ($cpus as $option): ?>
              <option value="<?= e($option) ?>" <?= (($filters['cpu'] ?? 'Any CPU') === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:160px;">
          <label>GPU</label>
          <select name="gpu">
            <?php foreach ($gpus as $option): ?>
              <option value="<?= e($option) ?>" <?= (($filters['gpu'] ?? 'Any GPU') === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:140px;">
          <label>Sort By</label>
          <select name="sort">
            <?php foreach ($sortOptions as $option): ?>
              <option value="<?= e($option) ?>" <?= (($filters['sort'] ?? 'Highest Score') === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div style="display:flex;gap:8px;align-items:flex-end;margin-top:18px;">
          <button type="submit" class="btn btn-primary btn-sm">Apply</button>
          <a href="leaderboard.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Clear</a>
        </div>
      </form>
    </div>

    <div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
      <span class="chip active">🏆 All</span>
      <span class="chip">CPU Only</span>
      <span class="chip">GPU Only</span>
      <span class="chip">My Hardware</span>
      <span class="chip">This Month</span>
    </div>

    <div class="card" style="padding:0;">
      <table>
        <thead>
          <tr>
            <th style="width:60px;">#</th>
            <th>User</th>
            <th>Composite ↓</th>
            <th>Submitted</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($leaderboardRows)): ?>
            <?php foreach ($leaderboardRows as $index => $row): ?>
              <tr>
                <td class="rank<?= (($index + 1) <= 3) ? ' top' : '' ?>"><?= e($row['rank'] ?? ($index + 1)) ?></td>
                <td><?= e($row['user'] ?? '') ?></td>
                <td class="score-val"><?= e(format_number_safe($row['composite_score'] ?? '')) ?></td>
                <td style="font-size:12px;color:var(--text-muted);font-family:'Courier Prime',monospace;"><?= e($row['submitted'] ?? '') ?></td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="4" style="padding:32px; text-align:center; color:var(--text-muted);">
                No data yet. Use <a href="submit-score.php" style="color:var(--blueprint);">Submit score</a> to add your first benchmark entry.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>

      <div style="padding:16px 20px; display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border);">
        <span style="font-size:13px; color:var(--text-muted); font-family:'Courier Prime',monospace;"><?= count($leaderboardRows) ?> scores</span>
      </div>
    </div>
  </div>
</div>
</body>
</html>