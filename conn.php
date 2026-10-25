<?php
require_once __DIR__ . '/base.php';

$host = "localhost";
$user = "root";
$pass = "";
$db   = "toko_jaringan";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>