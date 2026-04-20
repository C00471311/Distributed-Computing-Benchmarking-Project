<?php
require_once __DIR__ . '/helpers.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('admin.php');
}
$submissionId = (int)($_POST['submission_id'] ?? 0);
if ($submissionId <= 0) {
    set_flash('error', 'Invalid submission selected.');
    redirect('admin.php');
}
$stmt = db()->prepare("UPDATE Computers SET Status = 'approved', ReviewedAt = NOW(), RejectionReason = NULL WHERE ComputerID = ? AND Status = 'pending'");
$stmt->execute([$submissionId]);
set_flash('success', 'Submission approved successfully.');
redirect('admin.php');
