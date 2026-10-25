<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

$total_produk = query("SELECT COUNT(*) AS total FROM produk")[0]['total'];
$total_user   = query("SELECT COUNT(*) AS total FROM users WHERE role = 'customer'")[0]['total'];
$produk_restock = get_peringatan_restock();
$top_produk     = get_top_5_terlaris();
?>

<h2>Dashboard & Analisis UMKM</h2>

<div class="row mt-3 mb-4">
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-primary p-3">
            <h5>Total Produk Aktif</h5>
            <p class="fs-3 fw-bold mb-0"><?= $total_produk; ?></p>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card text-white bg-success p-3">
            <h5>Total Pelanggan Terdaftar</h5>
            <p class="fs-3 fw-bold mb-0"><?= $total_user; ?></p>
        </div>
    </div>
</div>

<!-- Komponen Komputasional 1: Rule-Based Restock Warning -->
<div class="card mb-4 border-warning shadow-sm">
    <div class="card-header bg-warning text-dark fw-bold">
        ⚠️ Peringatan Restock Produk (Stok < 5) [Komponen Komputasional Rule-Based]
    </div>
    <div class="card-body">
        <?php if (empty($produk_restock)): ?>
            <p class="text-success mb-0">Semua stok produk aman (di atas 5 unit).</p>
        <?php else: ?>
            <ul class="list-group">
                <?php foreach ($produk_restock as $r): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span><strong><?= htmlspecialchars($r['nama']); ?></strong> (Kategori: <?= $r['kategori']; ?>)</span>
                    <span class="badge bg-danger rounded-pill">Sisa Stok: <?= $r['stok']; ?> unit</span>
                </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<!-- Komponen Komputasional 2: Scoring 5 Produk Terlaris -->
<div class="card border-info shadow-sm">
    <div class="card-header bg-info text-white fw-bold">
        🏆 5 Produk Terlaris (Berdasarkan Penjualan) [Komponen Komputasional Scoring]
    </div>
    <div class="card-body">
        <?php if (empty($top_produk)): ?>
            <p class="text-muted mb-0">Belum ada transaksi pembayaran yang selesai.</p>
        <?php else: ?>
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Total Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($top_produk as $tp): ?>
                    <tr>
                        <td><strong>#<?= $no++; ?></strong></td>
                        <td><?= htmlspecialchars($tp['nama']); ?></td>
                        <td><?= htmlspecialchars($tp['kategori']); ?></td>
                        <td><span class="badge bg-primary"><?= $tp['total_terjual']; ?> unit</span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
