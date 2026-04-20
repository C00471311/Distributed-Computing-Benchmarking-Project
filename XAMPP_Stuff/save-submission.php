<?php
require_once __DIR__ . '/helpers.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('submit-score.php');
}

$cpu = trim((string)($_POST['cpu'] ?? ''));
$gpu = trim((string)($_POST['gpu'] ?? ''));
$operatingSystem = trim((string)($_POST['operating_system'] ?? ''));
$notes = trim((string)($_POST['submission_notes'] ?? ''));

$scores = [];
foreach (array_keys(benchmark_labels()) as $key) {
    $scores[$key] = normalize_score_value($_POST[$key] ?? null);
}
$nonNullScores = array_filter($scores, fn($v) => $v !== null && $v > 0);
$composite = normalize_score_value($_POST['composite_score'] ?? null) ?? compute_composite_from_scores($scores);

if ($cpu === '' || $gpu === '') {
    set_flash('error', 'CPU and GPU are required.');
    redirect('submit-score.php');
}
if (!$nonNullScores) {
    set_flash('error', 'Please enter at least one benchmark score.');
    redirect('submit-score.php');
}
if ($composite <= 0) {
    $composite = compute_composite_from_scores($scores);
}

$isFlagged = $composite >= 20000 ? 1 : 0;
$pdo = db();
$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('INSERT INTO Computers (UserID, CPU, GPU, OperatingSystem, UserComments, Rank, TotalBenchmarkingScore, Status, IsFlagged) VALUES (?, ?, ?, ?, ?, NULL, ?, ?, ?)');
    $stmt->execute([
        current_user_id(),
        $cpu,
        $gpu,
        $operatingSystem !== '' ? $operatingSystem : null,
        $notes !== '' ? $notes : null,
        $composite,
        'pending',
        $isFlagged,
    ]);
    $computerId = (int)$pdo->lastInsertId();

    $benchInsert = $pdo->prepare('INSERT INTO Benchmarks (ComputerID, BenchmarkingTool, CPUScore, GPUScore, TotalScore) VALUES (?, ?, NULL, NULL, ?)');
    foreach ($nonNullScores as $key => $value) {
        $benchInsert->execute([$computerId, benchmark_labels()[$key], $value]);
    }

    $pdo->commit();
    set_flash('success', 'Submission saved and sent to the admin review queue.');
    redirect('dashboard.php');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    set_flash('error', 'Could not save the submission. Please check your database connection and DB.sql import.');
    redirect('submit-score.php');
}
