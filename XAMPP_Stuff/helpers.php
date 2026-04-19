<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('e')) {
    function e($value): string {
        return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_number_safe')) {
    function format_number_safe($value): string {
        return is_numeric($value) ? number_format((float)$value) : (string)$value;
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array {
        return $_SESSION['user'] ?? null;
    }
}

if (!function_exists('is_logged_in')) {
    function is_logged_in(): bool {
        return !empty($_SESSION['user']);
    }
}

if (!function_exists('current_user_name')) {
    function current_user_name(): string {
        return trim((string)($_SESSION['user']['name'] ?? $_SESSION['user']['username'] ?? ''));
    }
}

if (!function_exists('current_user_role')) {
    function current_user_role(): string {
        return strtolower(trim((string)($_SESSION['user']['role'] ?? '')));
    }
}

if (!function_exists('is_admin_user')) {
    function is_admin_user(): bool {
        return current_user_role() === 'admin';
    }
}

if (!function_exists('user_initials')) {
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
}

if (!function_exists('render_header')) {
    function render_header(string $activePage = '', bool $adminMode = false): void {
        $loggedIn = is_logged_in();
        $name = current_user_name();
        $initials = user_initials($name);
        $isAdmin = is_admin_user();

        ?>
        <div class="page-header">
            <?php if ($adminMode): ?>
                <a href="admin.php" class="brand" style="text-decoration:none; color:inherit;">
                    BENCH<span>MARK</span> HUB
                    <span class="badge badge-admin" style="font-size:10px;vertical-align:middle;margin-left:8px;">ADMIN</span>
                </a>

                <nav>
                    <a href="admin.php" class="<?= $activePage === 'admin-queue' ? 'active' : '' ?>">Validation Queue</a>
                    <a href="admin-submissions.php" class="<?= $activePage === 'admin-submissions' ? 'active' : '' ?>">All Submissions</a>
                    <a href="admin-users.php" class="<?= $activePage === 'admin-users' ? 'active' : '' ?>">Users</a>
                    <a href="leaderboard.php" class="<?= $activePage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a>
                </nav>
            <?php else: ?>
                <a href="dashboard.php" class="brand" style="text-decoration:none; color:inherit;">
                    BENCH<span>MARK</span> HUB
                </a>

                <nav>
                    <a href="dashboard.php" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">Dashboard</a>
                    <a href="leaderboard.php" class="<?= $activePage === 'leaderboard' ? 'active' : '' ?>">Leaderboard</a>
                    <a href="submit-score.php" class="<?= $activePage === 'submit-score' ? 'active' : '' ?>">Submit Score</a>
                </nav>
            <?php endif; ?>

            <div class="header-actions">
                <?php if ($loggedIn): ?>
                    <div style="display:flex;align-items:center;gap:8px;">
                        <div class="avatar-placeholder" style="width:32px;height:32px;font-size:13px;border-color:#2a4a7f;color:#2a4a7f;">
                            <?= e($initials) ?>
                        </div>
                        <span style="font-size:13px;font-weight:600;">
                            <?= e($name !== '' ? $name : 'User') ?>
                            <?php if ($isAdmin): ?>
                                <span class="badge badge-admin" style="font-size:10px;">Admin</span>
                            <?php endif; ?>
                        </span>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="btn btn-sm btn-ghost">Login</a>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

if (!function_exists('bench_hardware_cpus')) {
    /**
     * Common CPUs for filters and submit-score datalist suggestions.
     * Submit form also allows free text for models not listed here.
     */
    function bench_hardware_cpus(): array {
        return [
            'AMD Ryzen 5 5600X',
            'AMD Ryzen 5 7600',
            'AMD Ryzen 7 5800X3D',
            'AMD Ryzen 7 7800X3D',
            'AMD Ryzen 7 9700X',
            'AMD Ryzen 9 7950X',
            'AMD Ryzen 9 9900X',
            'Intel Core i5-12400F',
            'Intel Core i5-13600K',
            'Intel Core i5-14600K',
            'Intel Core i7-13700K',
            'Intel Core i9-13900K',
            'Intel Core i9-14900K',
            'Intel Core Ultra 7 265K',
        ];
    }
}

if (!function_exists('bench_hardware_gpus')) {
    /**
     * Common GPUs for filters and submit-score datalist suggestions.
     * Submit form also allows free text for models not listed here.
     */
    function bench_hardware_gpus(): array {
        return [
            'AMD Radeon RX 6700 XT',
            'AMD Radeon RX 7800 XT',
            'AMD Radeon RX 7900 XT',
            'AMD Radeon RX 7900 XTX',
            'NVIDIA GeForce RTX 3060',
            'NVIDIA GeForce RTX 4060',
            'NVIDIA GeForce RTX 4060 Ti',
            'NVIDIA GeForce RTX 4070',
            'NVIDIA GeForce RTX 4070 Super',
            'NVIDIA GeForce RTX 4080',
            'NVIDIA GeForce RTX 4080 Super',
            'NVIDIA GeForce RTX 4090',
        ];
    }
}

if (!function_exists('bench_leaderboard_cpu_filter_options')) {
    function bench_leaderboard_cpu_filter_options(): array {
        return array_merge(['Any CPU'], bench_hardware_cpus());
    }
}

if (!function_exists('bench_leaderboard_gpu_filter_options')) {
    function bench_leaderboard_gpu_filter_options(): array {
        return array_merge(['Any GPU'], bench_hardware_gpus());
    }
}
?>
