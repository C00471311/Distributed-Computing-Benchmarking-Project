<?php
function bench_get_pdo(): PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('BENCH_DB_HOST') ?: '127.0.0.1';
    $port = getenv('BENCH_DB_PORT') ?: '3306';
    $dbname = getenv('BENCH_DB_NAME') ?: 'benchmark_hub';
    $username = getenv('BENCH_DB_USER') ?: 'root';
    $password = getenv('BENCH_DB_PASS') ?: '';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    return $pdo;
}
