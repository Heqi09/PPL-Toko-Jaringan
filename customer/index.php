<?php
require_once __DIR__ . '/../function.php';
include __DIR__ . '/../components/header.php';

$keyword = $_GET['keyword'] ?? '';
$kategori = $_GET['kategori'] ?? '';
$produk = get_katalog($keyword, $kategori);
?>

<div class="mb-4">
    <h2>Katalog Produk Jaringan</h2>
    <p class="text-muted">Temukan peralatan jaringan terbaik untuk kebutuhan UMKM Anda.</p>
</div>

<!-- Form Cari & Filter -->
<form method="get" class="row g-2 mb-4">
    <div class="col-md-5">
        <input type="text" name="keyword" class="form-control" placeholder="Cari nama produk atau brand..." value="<?= htmlspecialchars($keyword); ?>">
    </div>
    <div class="col-md-4">
        <select name="kategori" class="form-select">
            <option value="">Semua Kategori</option>
            <option value="Router" <?= $kategori === 'Router' ? 'selected' : ''; ?>>Router</option>
            <option value="Kabel UTP" <?= $kategori === 'Kabel UTP' ? 'selected' : ''; ?>>Kabel UTP</option>
            <option value="Switch" <?= $kategori === 'Switch' ? 'selected' : ''; ?>>Switch</option>
            <option value="Access Point" <?= $kategori === 'Access Point' ? 'selected' : ''; ?>>Access Point</option>
            <option value="Aksesori" <?= $kategori === 'Aksesori' ? 'selected' : ''; ?>>Aksesori</option>
        </select>
    </div>
    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
</form>

<!-- Daftar Produk -->
<div class="row">
    <?php if (empty($produk)): ?>
        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">Produk tidak ditemukan.</p>
        </div>
    <?php else: ?>
        <?php foreach ($produk as $p): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <?php
                    $gambar_path = (!empty($p['gambar']) && file_exists(__DIR__ . '/../assets/img/' . $p['gambar'])) 
                        ? $base_url . 'assets/img/' . htmlspecialchars($p['gambar']) 
                        : $base_url . 'assets/img/default.jpg';
                ?>
                <img src="<?= $gambar_path; ?>" class="card-img-top p-3" alt="<?= htmlspecialchars($p['nama']); ?>" style="height: 200px; object-fit: contain;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($p['nama']); ?></h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?= htmlspecialchars($p['kategori']); ?> | <?= htmlspecialchars($p['brand']); ?></h6>
                    <p class="card-text fw-bold text-primary fs-5 mt-auto">Rp <?= number_format($p['harga'], 0, ',', '.'); ?></p>
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <a href="detail-produk.php?id=<?= $p['id']; ?>" class="btn btn-outline-secondary btn-sm">Detail</a>
                        <a href="keranjang.php?action=add&id=<?= $p['id']; ?>" class="btn btn-success btn-sm">Tambah ke Keranjang</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/../components/footer.php'; ?>