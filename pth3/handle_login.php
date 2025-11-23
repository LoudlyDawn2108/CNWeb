<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.html');
    exit;
}

$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

if ($user === 'admin' && $pass === '123') {
    $_SESSION['username'] = $user;
    header('Location: welcome.php');
} else {
    header('Location: login.html?error=1');
}

exit;


