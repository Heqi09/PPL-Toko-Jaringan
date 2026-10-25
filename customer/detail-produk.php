<?php
require_once __DIR__ . '/../function.php';
include __DIR__ . '/../components/header.php';

$id = intval($_GET['id'] ?? 0);
$data = query("SELECT * FROM produk WHERE id = $id");

if (empty($data)) {
    echo "<div class='alert alert-danger py-4 text-center mt-3'>Produk tidak ditemukan! <a href='index.php'>Kembali ke katalog</a></div>";
    include __DIR__ . '/../components/footer.php';
    exit();
}

$p = $data[0];
$gambar_path = (!empty($p['gambar']) && file_exists(__DIR__ . '/../assets/img/' . $p['gambar'])) 
    ? $base_url . 'assets/img/' . htmlspecialchars($p['gambar']) 
    : $base_url . 'assets/img/default.jpg';
?>

<div class="row mt-4 mb-5">
    <div class="col-md-6 mb-4">
        <div class="bg-white p-3 text-center border rounded shadow-sm">
            <img src="<?= $gambar_path; ?>" class="img-fluid" alt="<?= htmlspecialchars($p['nama']); ?>" style="max-height: 380px; object-fit: contain;">
        </div>
    </div>
    <div class="col-md-6">
        <h2><?= htmlspecialchars($p['nama']); ?></h2>
        <h6 class="text-muted mb-3">
            <span class="badge bg-secondary"><?= htmlspecialchars($p['kategori']); ?></span>
            <span class="badge bg-outline-dark text-dark border ms-1"><?= htmlspecialchars($p['brand']); ?></span>
        </h6>
        <h3 class="text-primary fw-bold mb-3">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></h3>
        <p class="mb-3">Stok tersedia: <strong><?= $p['stok']; ?> unit</strong></p>
        
        <div class="mb-4">
            <h6 class="fw-bold">Deskripsi Produk:</h6>
            <p class="text-secondary"><?= nl2br(htmlspecialchars($p['deskripsi'])); ?></p>
        </div>
        
        <hr class="my-4">
        <div class="d-flex gap-2">
            <a href="keranjang.php?action=add&id=<?= $p['id']; ?>" class="btn btn-success btn-lg">Tambah Ke Keranjang</a>
            <a href="index.php" class="btn btn-outline-secondary btn-lg">Kembali ke Katalog</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>