<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/base.php';

function check_auth($role_required = null) {
    global $base_url;
    if (!isset($_SESSION['user'])) {
        header("Location: " . $base_url . "login/index.php");
        exit();
    }
    if ($role_required && $_SESSION['user']['role'] !== $role_required) {
        if ($_SESSION['user']['role'] === 'admin') {
            header("Location: " . $base_url . "admin/index.php");
        } else {
            header("Location: " . $base_url . "customer/index.php");
        }
        exit();
    }
}
?>