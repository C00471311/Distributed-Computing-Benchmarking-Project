<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/config/db.php';

function e($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function format_number_safe($value): string {
    return is_numeric($value) ? number_format((float)$value) : (string)$value;
}

function db(): PDO {
    return bench_get_pdo();
}

function redirect(string $path): void {
    header('Location: ' . $path, true, 302);
    exit;
}

function set_flash(string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array {
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function render_flash(): void {
    $flash = get_flash();
    if (!$flash) {
        return;
    }

    $bg = '#e8eef8';
    $border = '#2a4a7f';
    $color = '#2a4a7f';
    if (($flash['type'] ?? '') === 'error') {
        $bg = '#f8d7da';
        $border = '#dc3545';
        $color = '#721c24';
    } elseif (($flash['type'] ?? '') === 'success') {
        $bg = '#d4edda';
        $border = '#28a745';
        $color = '#155724';
    }

    echo '<div style="max-width:1200px;margin:18px auto 0;padding:12px 16px;border:1px solid ' . e($border) . ';background:' . e($bg) . ';color:' . e($color) . ';border-radius:6px;">' . e($flash['message'] ?? '') . '</div>';
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']);
}

function current_user_id(): ?int {
    return isset($_SESSION['user']['UserID']) ? (int)$_SESSION['user']['UserID'] : null;
}

function current_user_name(): string {
    return trim((string)($_SESSION['user']['UserName'] ?? ''));
}

function current_user_role(): string {
    return strtolower(trim((string)($_SESSION['user']['Role'] ?? 'user')));
}

function is_admin_user(): bool {
    return current_user_role() === 'admin';
}

function require_login(): void {
    if (!is_logged_in()) {
        set_flash('error', 'Please sign in first.');
        redirect('login.php');
    }
}

function require_admin(): void {
    require_login();
    if (!is_admin_user()) {
        set_flash('error', 'Administrator access is required.');
        redirect('dashboard.php');
    }
}

function user_initials(?string $name = null): string {
    $name = trim((string)($name ?? current_user_name()));
    if ($name === '') {
        return 'GU';
    }
    $parts = preg_split('/\s+/', $name);
    $parts = array_values(array_filter($parts));
    if (count($parts) >= 2) {
        return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
    }
    return strtoupper(substr($parts[0], 0, 2));
}

function bench_hardware_cpus(): array {
    return [
        'AMD Ryzen 5 5600X', 'AMD Ryzen 5 7600', 'AMD Ryzen 7 5800X3D', 'AMD Ryzen 7 7800X3D',
        'AMD Ryzen 7 9700X', 'AMD Ryzen 9 7950X', 'AMD Ryzen 9 9900X', 'Intel Core i5-12400F',
        'Intel Core i5-13600K', 'Intel Core i5-14600K', 'Intel Core i7-13700K', 'Intel Core i9-13900K',
        'Intel Core i9-14900K', 'Intel Core Ultra 7 265K',
    ];
}

function bench_hardware_gpus(): array {
    return [
        'AMD Radeon RX 6700 XT', 'AMD Radeon RX 7800 XT', 'AMD Radeon RX 7900 XT', 'AMD Radeon RX 7900 XTX',
        'NVIDIA GeForce RTX 3060', 'NVIDIA GeForce RTX 4060', 'NVIDIA GeForce RTX 4060 Ti', 'NVIDIA GeForce RTX 4070',
        'NVIDIA GeForce RTX 4070 Super', 'NVIDIA GeForce RTX 4080', 'NVIDIA GeForce RTX 4080 Super', 'NVIDIA GeForce RTX 4090',
    ];
}

function bench_leaderboard_cpu_filter_options(): array {
    return array_merge(['Any CPU'], bench_hardware_cpus());
}

function bench_leaderboard_gpu_filter_options(): array {
    return array_merge(['Any GPU'], bench_hardware_gpus());
}

function benchmark_labels(): array {
    return [
        'basemark' => 'Basemark',
        '3dmark' => '3DMark',
        'novabench' => 'Novabench',
        'sysmark' => 'BAPco SYSmark',
        'passmark' => 'Passmark',
    ];
}

function normalize_score_value($value): ?int {
    if ($value === null || $value === '') {
        return null;
    }
    if (!is_numeric($value)) {
        return null;
    }
    $n = (float)$value;
    if ($n < 0) {
        return null;
    }
    return (int) round($n);
}

function compute_composite_from_scores(array $scores): int {
    $valid = [];
    foreach ($scores as $score) {
        $n = normalize_score_value($score);
        if ($n !== null && $n > 0) {
            $valid[] = $n;
        }
    }
    if (!$valid) {
        return 0;
    }
    return (int) round(array_sum($valid) / count($valid));
}

function benchmark_status_badge_class(string $status): string {
    return match (strtolower($status)) {
        'approved' => 'badge-approved',
        'rejected' => 'badge-rejected',
        default => 'badge-pending',
    };
}

function render_header(string $activePage = ''): void {
    $loggedIn = is_logged_in();
    $name = current_user_name();
    $initials = user_initials($name);
    $isAdmin = is_admin_user();
    ?>
    <div class="page-header">
        <a href="dashboard.php" class="brand" style="text-decoration:none; color:inherit;">BENCH<span>MARK</span> HUB</a>
        <nav>
            <a href="dashboard.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
            <a href="leaderboard.php" class="<?= $activePage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a>
            <a href="submit-score.php" class="<?= $activePage === 'submit-score' ? 'active' : '' ?>">Submit Score</a>
            <?php if ($isAdmin): ?>
                <a href="admin.php" class="<?= $activePage === 'admin' ? 'active' : '' ?>">Admin</a>
            <?php endif; ?>
        </nav>

        <div class="header-actions">
            <?php if ($loggedIn): ?>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div class="avatar-placeholder" style="width:32px;height:32px;font-size:13px;border-color:#2a4a7f;color:#2a4a7f;"><?= e($initials) ?></div>
                    <span style="font-size:13px;font-weight:600;">
                        <?= e($name !== '' ? $name : 'User') ?>
                        <?php if ($isAdmin): ?><span class="badge badge-admin" style="font-size:10px;">Admin</span><?php endif; ?>
                    </span>
                </div>
                <a href="logout.php" class="btn btn-sm btn-ghost" style="text-decoration:none;">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-sm btn-ghost" style="text-decoration:none;">Login</a>
            <?php endif; ?>
        </div>
    </div>
    <?php
}
