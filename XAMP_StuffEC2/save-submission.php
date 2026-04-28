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

if ($cpu === '' || $gpu === '') {
    set_flash('error', 'CPU and GPU are required.');
    redirect('submit-score.php');
}

if (!$nonNullScores) {
    set_flash('error', 'Please enter at least one benchmark score.');
    redirect('submit-score.php');
}

function compute_composite_from_ec2(array $scores): ?int {
    $apiUrl = 'http://18.117.84.18:5000/score';

    $payload = json_encode([
        'scores' => $scores
    ]);

    $options = [
        'http' => [
            'header' => "Content-Type: application/json\r\n",
            'method' => 'POST',
            'content' => $payload,
            'timeout' => 10,
        ]
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($apiUrl, false, $context);

    if ($response === false) {
        return null;
    }

    $result = json_decode($response, true);

    if (!is_array($result) || !isset($result['composite_score'])) {
        return null;
    }

    return (int)$result['composite_score'];
}

$composite = compute_composite_from_ec2($scores);

if ($composite === null || $composite <= 0) {
    set_flash('error', 'Could not reach EC2 scoring API. Please make sure the EC2 instance and Python API are running.');
    redirect('submit-score.php');
}

$isFlagged = $composite >= 20000 ? 1 : 0;

$pdo = db();
$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("
        INSERT INTO Computers (
            UserID,
            CPU,
            GPU,
            OperatingSystem,
            UserComments,
            Rank,
            TotalBenchmarkingScore,
            Status,
            IsFlagged
        )
        VALUES (?, ?, ?, ?, ?, NULL, ?, ?, ?)
    ");

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

    $benchInsert = $pdo->prepare("
        INSERT INTO Benchmarks (
            ComputerID,
            BenchmarkingTool,
            CPUScore,
            GPUScore,
            TotalScore
        )
        VALUES (?, ?, NULL, NULL, ?)
    ");

    foreach ($nonNullScores as $key => $value) {
        $benchInsert->execute([
            $computerId,
            benchmark_labels()[$key],
            $value,
        ]);
    }

    $pdo->commit();

    set_flash('success', 'Submission saved. Composite score was calculated by the EC2 API.');
    redirect('dashboard.php');
} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    set_flash('error', 'Could not save the submission. Please check your database connection and DB.sql import.');
    redirect('submit-score.php');
}