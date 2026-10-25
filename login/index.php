<?php
require_once __DIR__ . '/../session.php';
require_once __DIR__ . '/../function.php';

$error = '';

if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'admin') {
        header("Location: " . $base_url . "admin/index.php");
    } else {
        header("Location: " . $base_url . "customer/index.php");
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $user = login($username, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        
        // Pengarahan halaman sesuai role user
        if ($user['role'] === 'admin') {
            header("Location: " . $base_url . "admin/index.php");
        } else {
            header("Location: " . $base_url . "customer/index.php");
        }
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Toko Jaringan UMKM</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h4 class="card-title text-center mb-4">Login Toko Jaringan</h4>
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-2"><?= $error; ?></div>
                    <?php endif; ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Masuk</button>
                    </form>
                    <div class="text-center mt-3">
                        <small>Belum punya akun? <a href="registrasi.php">Daftar Akun</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>