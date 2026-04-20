<?php
require_once __DIR__ . '/helpers.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.php');
}

$username = trim((string)($_POST['username'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$password = (string)($_POST['password'] ?? '');
$passwordConfirm = (string)($_POST['password_confirm'] ?? '');

if ($username === '' || $email === '' || $password === '') {
    set_flash('error', 'All sign-up fields are required.');
    redirect('login.php');
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Please enter a valid email address.');
    redirect('login.php');
}
if ($password !== $passwordConfirm) {
    set_flash('error', 'Passwords do not match.');
    redirect('login.php');
}
if (strlen($password) < 6) {
    set_flash('error', 'Password must be at least 6 characters long.');
    redirect('login.php');
}

$pdo = db();
$check = $pdo->prepare('SELECT UserID FROM Accounts WHERE UserName = ? OR Email = ? LIMIT 1');
$check->execute([$username, $email]);
if ($check->fetch()) {
    set_flash('error', 'That username or email is already in use.');
    redirect('login.php');
}

$stmt = $pdo->prepare('INSERT INTO Accounts (UserName, Email, PasswordHashed, Verification, Role) VALUES (?, ?, ?, 1, ?)');
$stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT), 'user']);
$userId = (int)$pdo->lastInsertId();
$_SESSION['user'] = ['UserID' => $userId, 'UserName' => $username, 'Email' => $email, 'Role' => 'user'];
set_flash('success', 'Account created successfully.');
redirect('dashboard.php');
