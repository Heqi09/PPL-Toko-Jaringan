<?php
require_once 'session.php';

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: " . $base_url . "admin/index.php");
    } else {
        header("Location: " . $base_url . "customer/index.php");
    }
} else {
    header("Location: " . $base_url . "login/index.php");
}
exit();
?>