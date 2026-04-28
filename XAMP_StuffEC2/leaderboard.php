<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/config/db.php';

$pdo = bench_get_pdo();

$benchmarks = ['All Benchmarks', 'Basemark', '3DMark', 'Novabench', 'BAPco SYSmark', 'Passmark'];
$cpus = bench_leaderboard_cpu_filter_options();
$gpus = bench_leaderboard_gpu_filter_options();
$sortOptions = ['Highest Score', 'Lowest Score', 'Near My Score'];

$filters = [
    'benchmark' => $_GET['benchmark'] ?? 'All Benchmarks',
    'cpu' => $_GET['cpu'] ?? 'Any CPU',
    'gpu' => $_GET['gpu'] ?? 'Any GPU',
    'sort' => $_GET['sort'] ?? 'Highest Score',
];

$quick = $_GET['quick'] ?? '';

$where = ["c.Status = 'approved'"];
$params = [];

if ($filters['benchmark'] !== 'All Benchmarks') {
    $where[] = "EXISTS (
        SELECT 1
        FROM Benchmarks bx
        WHERE bx.ComputerID = c.ComputerID
          AND bx.BenchmarkingTool = ?
    )";
    $params[] = $filters['benchmark'];
}

if ($filters['cpu'] !== 'Any CPU') {
    $where[] = "c.CPU = ?";
    $params[] = $filters['cpu'];
}

if ($filters['gpu'] !== 'Any GPU') {
    $where[] = "c.GPU = ?";
    $params[] = $filters['gpu'];
}

if ($quick === 'cpu') {
    $where[] = "c.CPU <> ''";
}

if ($quick === 'gpu') {
    $where[] = "c.GPU <> ''";
}

if ($quick === 'my' && is_logged_in()) {
    $where[] = "a.UserID = ?";
    $params[] = (int)($_SESSION['user']['UserID'] ?? 0);
}

if ($quick === 'month') {
    $where[] = "MONTH(c.CreatedAt) = MONTH(CURRENT_DATE()) AND YEAR(c.CreatedAt) = YEAR(CURRENT_DATE())";
}

$orderBy = "c.TotalBenchmarkingScore DESC";

if ($filters['sort'] === 'Lowest Score') {
    $orderBy = "c.TotalBenchmarkingScore ASC";
} elseif ($filters['sort'] === 'Near My Score' && is_logged_in()) {
    $myUserId = (int)($_SESSION['user']['UserID'] ?? 0);
    $myScore = 0;

    $myStmt = $pdo->prepare("
        SELECT MAX(TotalBenchmarkingScore) AS MyTopScore
        FROM Computers
        WHERE UserID = ?
    ");
    $myStmt->execute([$myUserId]);
    $myRow = $myStmt->fetch();

    if ($myRow) {
        $myScore = (int)($myRow['MyTopScore'] ?? 0);
    }

    if ($myScore > 0) {
        $orderBy = "ABS(c.TotalBenchmarkingScore - " . (int)$myScore . ") ASC, c.TotalBenchmarkingScore DESC";
    }
}

$sql = "
    SELECT
        c.ComputerID,
        c.TotalBenchmarkingScore,
        c.CreatedAt,
        c.UserComments,
        c.CPU,
        c.GPU,
        a.UserName
    FROM Computers c
    INNER JOIN Accounts a ON c.UserID = a.UserID
";

if (!empty($where)) {
    $sql .= " WHERE " . implode(' AND ', $where);
}

$sql .= " ORDER BY " . $orderBy;

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$leaderboardRows = [];
$computerIds = [];
$rank = 1;

while ($row = $stmt->fetch()) {
    $computerId = (int)$row['ComputerID'];
    $computerIds[] = $computerId;

    $leaderboardRows[] = [
        'computer_id' => $computerId,
        'rank' => $rank++,
        'user' => $row['UserName'],
        'cpu' => $row['CPU'] ?? '',
        'gpu' => $row['GPU'] ?? '',
        'composite_score' => $row['TotalBenchmarkingScore'],
        'submitted' => !empty($row['CreatedAt']) ? date('Y-m-d', strtotime($row['CreatedAt'])) : '',
        'notes' => trim((string)($row['UserComments'] ?? '')),
    ];
}

$benchmarkDetails = [];

if (!empty($computerIds)) {
    $placeholders = implode(',', array_fill(0, count($computerIds), '?'));
    $detailSql = "
        SELECT ComputerID, BenchmarkingTool, TotalScore
        FROM Benchmarks
        WHERE ComputerID IN ($placeholders)
        ORDER BY BenchmarkingTool ASC
    ";
    $detailStmt = $pdo->prepare($detailSql);
    $detailStmt->execute($computerIds);

    while ($detail = $detailStmt->fetch()) {
        $cid = (int)$detail['ComputerID'];
        if (!isset($benchmarkDetails[$cid])) {
            $benchmarkDetails[$cid] = [];
        }
        $benchmarkDetails[$cid][] = [
            'tool' => $detail['BenchmarkingTool'],
            'score' => $detail['TotalScore'],
        ];
    }
}

function quick_chip_link(string $label, string $value, string $currentQuick): string {
    $params = $_GET;

    if ($value === '') {
        unset($params['quick']);
    } else {
        $params['quick'] = $value;
    }

    $href = 'leaderboard.php';
    if (!empty($params)) {
        $href .= '?' . http_build_query($params);
    }

    $active = ($currentQuick === $value || ($value === '' && $currentQuick === '')) ? ' active' : '';
    return '<a href="' . e($href) . '" class="chip' . $active . '" style="text-decoration:none;">' . e($label) . '</a>';
}
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
              <option value="<?= e($option) ?>" <?= ($filters['benchmark'] === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:160px;">
          <label>CPU</label>
          <select name="cpu">
            <?php foreach ($cpus as $option): ?>
              <option value="<?= e($option) ?>" <?= ($filters['cpu'] === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:160px;">
          <label>GPU</label>
          <select name="gpu">
            <?php foreach ($gpus as $option): ?>
              <option value="<?= e($option) ?>" <?= ($filters['gpu'] === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="form-field" style="min-width:140px;">
          <label>Sort By</label>
          <select name="sort">
            <?php foreach ($sortOptions as $option): ?>
              <option value="<?= e($option) ?>" <?= ($filters['sort'] === $option) ? 'selected' : '' ?>>
                <?= e($option) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <?php if ($quick !== ''): ?>
          <input type="hidden" name="quick" value="<?= e($quick) ?>">
        <?php endif; ?>

        <div style="display:flex;gap:8px;align-items:flex-end;margin-top:18px;">
          <button type="submit" class="btn btn-primary btn-sm">Apply</button>
          <a href="leaderboard.php" class="btn btn-ghost btn-sm" style="text-decoration:none;">Clear</a>
        </div>
      </form>
    </div>

    <div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
      <?= quick_chip_link('🏆 All', '', $quick) ?>
      <?= quick_chip_link('CPU Only', 'cpu', $quick) ?>
      <?= quick_chip_link('GPU Only', 'gpu', $quick) ?>
      <?= quick_chip_link('My Hardware', 'my', $quick) ?>
      <?= quick_chip_link('This Month', 'month', $quick) ?>
    </div>

    <div class="card" style="padding:0;">
      <table>
        <thead>
          <tr>
            <th style="width:60px;">#</th>
            <th>User</th>
            <th>Composite ↓</th>
            <th>Submitted</th>
            <th style="width:110px;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php if (!empty($leaderboardRows)): ?>
            <?php foreach ($leaderboardRows as $index => $row): ?>
              <?php $details = $benchmarkDetails[$row['computer_id']] ?? []; ?>
              <tr>
                <td class="rank<?= (($index + 1) <= 3) ? ' top' : '' ?>"><?= e($row['rank']) ?></td>
                <td><?= e($row['user']) ?></td>
                <td class="score-val"><?= e(format_number_safe($row['composite_score'])) ?></td>
                <td style="font-size:12px;color:var(--text-muted);font-family:'Courier Prime',monospace;"><?= e($row['submitted']) ?></td>
                <td>
                  <button
                    type="button"
                    class="view-btn"
                    data-target="details-<?= e((string)$row['computer_id']) ?>"
                    aria-expanded="false"
                  >
                    View →
                  </button>
                </td>
              </tr>
              <tr id="details-<?= e((string)$row['computer_id']) ?>" class="detail-row">
                <td colspan="5" class="detail-cell">
                  <div class="detail-meta">
                    <span><strong>CPU:</strong> <?= e($row['cpu']) ?></span>
                    <span><strong>GPU:</strong> <?= e($row['gpu']) ?></span>
                  </div>

                  <div class="detail-grid">
                    <div class="detail-box">
                      <div class="detail-box-title">Benchmark Scores</div>
                      <?php if (!empty($details)): ?>
                        <div class="detail-benchmark-list">
                          <?php foreach ($details as $detail): ?>
                            <div class="detail-benchmark-item">
                              <span><?= e($detail['tool']) ?></span>
                              <span class="score-val" style="font-size:14px;"><?= e(format_number_safe($detail['score'])) ?></span>
                            </div>
                          <?php endforeach; ?>
                        </div>
                      <?php else: ?>
                        <div style="font-size:13px; color:var(--text-muted);">No benchmark breakdown available.</div>
                      <?php endif; ?>
                    </div>

                    <div class="detail-box">
                      <div class="detail-box-title">Submission Notes</div>
                      <div class="detail-note">
                        <?= e($row['notes'] !== '' ? $row['notes'] : 'No notes provided.') ?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="5" style="padding:32px; text-align:center; color:var(--text-muted);">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.view-btn');

  buttons.forEach(function (button) {
    button.addEventListener('click', function () {
      const targetId = button.getAttribute('data-target');
      const row = document.getElementById(targetId);
      const isOpen = row.classList.contains('is-open');

      document.querySelectorAll('.detail-row.is-open').forEach(function (openRow) {
        openRow.classList.remove('is-open');
      });

      document.querySelectorAll('.view-btn[aria-expanded="true"]').forEach(function (openButton) {
        openButton.setAttribute('aria-expanded', 'false');
        openButton.textContent = 'View →';
      });

      if (!isOpen) {
        row.classList.add('is-open');
        button.setAttribute('aria-expanded', 'true');
        button.textContent = 'Hide ↑';
      }
    });
  });
});
</script>
</body>
</html>