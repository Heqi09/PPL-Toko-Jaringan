<?php
require_once __DIR__ . '/../function.php';
$error = '';
$success = '';

if (isset($_SESSION['user'])) {
    header("Location: " . $base_url . "index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (registrasi($_POST)) {
        $success = "Registrasi berhasil! Silakan login.";
    } else {
        $error = "Username sudah digunakan atau registrasi gagal.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Registrasi - Toko Jaringan UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4">Daftar Akun Baru</h4>
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2"><?= $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success py-2"><?= $success; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Daftar</button>
                    </form>
                    <div class="text-center mt-3">
                        <small>Sudah punya akun? <a href="index.php">Login di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>