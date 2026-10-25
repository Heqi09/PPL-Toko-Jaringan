<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

// Mengambil data ringkasan untuk statistik dashboard
$total_produk = query("SELECT COUNT(*) AS total FROM produk")[0]['total'];
$total_user = query("SELECT COUNT(*) AS total FROM users WHERE role = 'customer'")[0]['total'];
?>

<div class="mb-4">
    <h2>Dashboard Admin</h2>
    <p>Selamat datang, <strong><?= htmlspecialchars($_SESSION['user']['nama']); ?></strong>!</p>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Produk</h5>
                <p class="card-text fs-3"><?= $total_produk; ?></p>
                <a href="produk-list.php" class="btn btn-light btn-sm">Kelola Produk</a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Pelanggan Registered</h5>
                <p class="card-text fs-3"><?= $total_user; ?></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>