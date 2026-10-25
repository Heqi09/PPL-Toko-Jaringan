<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../base.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Toko Jaringan UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= $base_url; ?>">Toko Jaringan</a>
    <div class="navbar-nav ms-auto">
      <?php if (isset($_SESSION['user'])): ?>
        <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a class="nav-link" href="<?= $base_url; ?>admin/index.php">Dashboard Admin</a>
            <a class="nav-link" href="<?= $base_url; ?>admin/produk-list.php">Kelola Produk</a>
        <?php else: ?>
            <a class="nav-link" href="<?= $base_url; ?>customer/index.php">Katalog</a>
            <a class="nav-link" href="<?= $base_url; ?>customer/keranjang.php">Keranjang</a>
        <?php endif; ?>
        <a class="nav-link text-danger" href="<?= $base_url; ?>logout.php">Logout (<?= $_SESSION['user']['username']; ?>)</a>
      <?php else: ?>
        <a class="nav-link" href="<?= $base_url; ?>login/index.php">Login</a>
        <a class="nav-link" href="<?= $base_url; ?>login/registrasi.php">Registrasi</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<div class="container">