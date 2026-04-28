<?php
require_once __DIR__ . '/helpers.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin.php');
}
$submissionId = (int)($_POST['submission_id'] ?? 0);
$reason = trim((string)($_POST['custom_reason'] ?? ''));
if ($reason === '') {
    $reason = trim((string)($_POST['reason'] ?? 'Rejected by administrator'));
}
if ($submissionId <= 0) {
    set_flash('error', 'Invalid submission selected.');
    redirect('admin.php');
}
$stmt = db()->prepare("UPDATE Computers SET Status = 'rejected', ReviewedAt = NOW(), RejectionReason = ? WHERE ComputerID = ? AND Status = 'pending'");
$stmt->execute([$reason, $submissionId]);
set_flash('success', 'Submission rejected successfully.');
redirect('admin.php');
