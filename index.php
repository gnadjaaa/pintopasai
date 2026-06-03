<?php
session_start();

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$script_directory = dirname($_SERVER['SCRIPT_NAME']);
$base_url = $protocol . $host . rtrim($script_directory, '/\\');

if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    $role = $_SESSION['role'];

    if ($role === 'pemasok') {
        header('Location: ' . $base_url . '/dashboard.php');
        exit;
    } elseif ($role === 'pembeli') {
        header('Location: ' . $base_url . '/pembeli.php');
        exit;
    } else {
        session_unset();
        session_destroy();
        header('Location: ' . $base_url . '/login.html');
        exit;
    }
} else {
    header('Location: ' . $base_url . '/login.html');
    exit;
}
?>