<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

if (isset($_GET['action']) && $_GET['action'] === 'delete') {
    hapus_produk($_GET['id']);
    header("Location: produk-list.php");
    exit();
}

$produk = query("SELECT * FROM produk ORDER BY id DESC");
?>

<div class="d-flex justify-content-between mb-3">
    <h3>Daftar Produk (Admin)</h3>
    <a href="produk-form.php" class="btn btn-primary">+ Tambah Produk</a>
</div>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produk as $p): ?>
        <tr>
            <td><?= $p['id']; ?></td>
            <td><?= htmlspecialchars($p['nama']); ?></td>
            <td><?= $p['kategori']; ?></td>
            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
            <td><?= $p['stok']; ?></td>
            <td>
                <a href="produk-form.php?id=<?= $p['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="produk-list.php?action=delete&id=<?= $p['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../components/footer.php'; ?>