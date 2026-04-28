<?php
require_once __DIR__ . '/helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
if ($username === '' || $password === '') {
    set_flash('error', 'Please enter both username and password.');
    redirect('login.php');
}

$stmt = db()->prepare('SELECT UserID, UserName, Email, PasswordHashed, Role, Verification FROM Accounts WHERE UserName = ? LIMIT 1');
$stmt->execute([$username]);
$user = $stmt->fetch();
if (!$user || !password_verify($password, (string)$user['PasswordHashed'])) {
    set_flash('error', 'Invalid username or password.');
    redirect('login.php');
}

$_SESSION['user'] = [
    'UserID' => (int)$user['UserID'],
    'UserName' => $user['UserName'],
    'Email' => $user['Email'],
    'Role' => $user['Role'],
    'Verification' => (int)$user['Verification'],
];
set_flash('success', 'Welcome back, ' . $user['UserName'] . '.');
redirect(is_admin_user() ? 'admin.php' : 'dashboard.php');
