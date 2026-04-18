<?php require_once __DIR__ . '/helpers.php';
$stats = $stats ?? ['pending_review' => 0, 'approved_this_month' => 0, 'rejected_this_month' => 0, 'flagged' => 0];
$pendingSubmissions = $pendingSubmissions ?? [];
$selectedSubmission = $selectedSubmission ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Benchmark Hub — Admin Panel</title>
<link href="https://fonts.googleapis.com/css2?family=Courier+Prime:wght@400;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="benchmark-hub.css">
</head>
<body>
<div class="page" id="page-admin">
  <?php render_header(); ?>

  <div class="content-wrap">

    <div class="three-col" style="margin-top:20px; margin-bottom:24px;">
      <div class="card" style="text-align:center;">
        <div class="stat-big" style="color:var(--blueprint);"><?= e(format_number_safe($stats['pending_review'] ?? 0)) ?></div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:6px;font-family:'Courier Prime',monospace;text-transform:uppercase;letter-spacing:0.06em;">Pending Review</div>
      </div>
      <div class="card" style="text-align:center;">
        <div class="stat-big" style="color:var(--green);"><?= e(format_number_safe($stats['approved_this_month'] ?? 0)) ?></div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:6px;font-family:'Courier Prime',monospace;text-transform:uppercase;letter-spacing:0.06em;">Approved This Month</div>
      </div>
      <div class="card" style="text-align:center;">
        <div class="stat-big" style="color:var(--red);"><?= e(format_number_safe($stats['rejected_this_month'] ?? 0)) ?></div>
        <div style="font-size:12px;color:var(--text-muted);margin-top:6px;font-family:'Courier Prime',monospace;text-transform:uppercase;letter-spacing:0.06em;">Rejected This Month</div>
      </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 360px; gap:24px;">
      <div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
          <h2 style="font-family:'Courier Prime',monospace;font-size:18px;font-weight:700;">Validation Queue</h2>
          <div class="filter-row">
            <span class="chip active">All (<?= count($pendingSubmissions) ?>)</span>
            <span class="chip">Flagged (<?= e((string)($stats['flagged'] ?? 0)) ?>)</span>
            <span class="chip">Oldest First</span>
          </div>
        </div>

        <div class="card" style="padding:0;">
          <table>
            <thead>
              <tr>
                <th>Submitted By</th>
                <th>Benchmark</th>
                <th>Score</th>
                <th>Submitted</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($pendingSubmissions)): ?>
                <?php foreach ($pendingSubmissions as $submission): ?>
                  <tr<?= !empty($submission['is_flagged']) ? ' style="background:#fff8e1;"' : '' ?>>
                    <td>
                      <div style="display:flex;gap:8px;align-items:center;">
                        <div class="avatar-placeholder" style="width:24px;height:24px;font-size:10px;"><?= e($submission['initials'] ?? '??') ?></div>
                        <div>
                          <div style="font-size:13px;font-weight:600;"><?= e($submission['username'] ?? '') ?></div>
                          <div style="font-size:11px;color:var(--text-muted);"><?= e($submission['account_meta'] ?? '') ?></div>
                        </div>
                      </div>
                    </td>
                    <td style="font-size:13px;"><?= e($submission['benchmark'] ?? '') ?></td>
                    <td class="score-val"<?= !empty($submission['score_color']) ? ' style="color:' . e($submission['score_color']) . ';"' : '' ?>><?= e($submission['score_display'] ?? '') ?></td>
                    <td style="font-size:12px;color:var(--text-muted);font-family:'Courier Prime',monospace;"><?= e($submission['submitted'] ?? '') ?></td>
                    <td><span class="badge badge-pending"><?= e($submission['status'] ?? 'Pending') ?></span></td>
                    <td>
                      <div class="admin-action-cell">
                        <form method="post" action="approve-submission.php">
                          <input type="hidden" name="submission_id" value="<?= e($submission['id'] ?? '') ?>">
                          <button class="btn btn-success btn-sm" type="submit">✓ Approve</button>
                        </form>
                        <form method="post" action="reject-submission.php">
                          <input type="hidden" name="submission_id" value="<?= e($submission['id'] ?? '') ?>">
                          <button class="btn btn-danger btn-sm" type="submit">✕ Reject</button>
                        </form>
                        <form method="get" action="admin.php">
                          <input type="hidden" name="submission_id" value="<?= e($submission['id'] ?? '') ?>">
                          <button class="btn btn-sm" type="submit">👁 View</button>
                        </form>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="padding:32px; text-align:center; color:var(--text-muted);">No submissions are waiting for review.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>

          <div style="padding:14px 16px; border-top:1px solid var(--border); display:flex; gap:10px;">
            <button class="btn btn-success btn-sm" type="button">✓ Approve All Visible</button>
            <button class="btn btn-ghost btn-sm" type="button">Export Queue CSV</button>
          </div>
        </div>

        <div class="annotation-block" style="margin-top:12px;">
          Row highlighted yellow = flagged by auto-detection (score >3σ from mean for that CPU/GPU combo).<br>
          "Approve" → immediately publishes to leaderboard. "Reject" → opens modal with reason field (sent to user via notification).<br>
          "View" → opens Score Detail slide-out panel for full review with screenshot upload.
        </div>
      </div>

      <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card">
          <div class="card-title">Selected Submission Detail</div>

          <?php if ($selectedSubmission): ?>
            <div style="display:flex;gap:12px;align-items:center;margin-bottom:16px;">
              <div class="avatar-placeholder"><?= e($selectedSubmission['initials'] ?? '??') ?></div>
              <div>
                <div style="font-weight:700;font-size:15px;"><?= e($selectedSubmission['username'] ?? '') ?></div>
                <div style="font-size:12px;color:var(--text-muted);font-family:'Courier Prime',monospace;"><?= e($selectedSubmission['account_created'] ?? '') ?></div>
                <span class="badge badge-pending"><?= e($selectedSubmission['detail_status'] ?? 'Pending') ?></span>
              </div>
            </div>

            <hr class="divider">

            <div style="display:flex;flex-direction:column;gap:10px;font-size:13px;">
              <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);">Benchmark</span><span style="font-weight:600;"><?= e($selectedSubmission['benchmark'] ?? '') ?></span></div>
              <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);">Submitted Score</span><span style="font-weight:700;color:var(--red);"><?= e($selectedSubmission['submitted_score'] ?? '') ?></span></div>
              <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);">CPU Listed</span><span style="font-weight:600;"><?= e($selectedSubmission['cpu'] ?? '') ?></span></div>
              <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);">GPU Listed</span><span style="font-weight:600;"><?= e($selectedSubmission['gpu'] ?? '') ?></span></div>
              <div style="display:flex;justify-content:space-between;"><span style="color:var(--text-muted);">Expected Range</span><span style="font-weight:600;color:var(--green);"><?= e($selectedSubmission['expected_range'] ?? '') ?></span></div>
            </div>

            <hr class="divider">

            <div class="annotation-block">
              <?= nl2br(e($selectedSubmission['notes'] ?? 'Screenshot/proof upload area would appear here.')) ?>
            </div>
          <?php else: ?>
            <div style="font-size:13px; color:var(--text-muted); line-height:1.6;">Choose a submission from the queue to review its details here.</div>
          <?php endif; ?>
        </div>

        <div class="card">
          <div class="card-title">Reject — Reason</div>
          <form method="post" action="reject-submission.php">
            <?php if ($selectedSubmission): ?>
              <input type="hidden" name="submission_id" value="<?= e($selectedSubmission['id'] ?? '') ?>">
            <?php endif; ?>

            <div class="form-field">
              <label>Reason (sent to user)</label>
              <select name="reason">
                <option>Score outside expected range for hardware</option>
                <option>Missing proof screenshot</option>
                <option>Incorrect benchmark preset</option>
                <option>Duplicate submission</option>
                <option>Custom reason...</option>
              </select>
            </div>

            <button class="btn btn-danger" style="width:100%;margin-top:12px;padding:10px;" type="submit">✕ Reject & Notify User</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>