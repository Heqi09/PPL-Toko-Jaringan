<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

$id = $_GET['id'] ?? null;
$produk = ['nama' => '', 'kategori' => 'Router', 'harga' => '', 'stok' => '', 'brand' => '', 'deskripsi' => '', 'gambar' => 'default.jpg'];
$is_edit = false;

if ($id) {
    $data = query("SELECT * FROM produk WHERE id = " . intval($id));
    if (!empty($data)) {
        $produk = $data[0];
        $is_edit = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = mysqli_real_escape_string($conn, $_POST['nama']);
    $kategori  = mysqli_real_escape_string($conn, $_POST['kategori']);
    $harga     = intval($_POST['harga']);
    $stok      = intval($_POST['stok']);
    $brand     = mysqli_real_escape_string($conn, $_POST['brand']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $gambarLama = $_POST['gambarLama'] ?? 'default.jpg';

    // Cek apakah admin mengunggah gambar baru
    if ($_FILES['gambar']['error'] === 4) {
        $gambar = $gambarLama;
    } else {
        $gambar = upload_gambar();
    }

    if ($gambar !== false) {
        if ($is_edit) {
            $sql = "UPDATE produk SET 
                    nama='$nama', 
                    kategori='$kategori', 
                    harga=$harga, 
                    stok=$stok, 
                    brand='$brand', 
                    deskripsi='$deskripsi', 
                    gambar='$gambar' 
                    WHERE id = " . intval($id);
            mysqli_query($conn, $sql);
        } else {
            $sql = "INSERT INTO produk (nama, kategori, harga, stok, brand, deskripsi, gambar) 
                    VALUES ('$nama', '$kategori', $harga, $stok, '$brand', '$deskripsi', '$gambar')";
            mysqli_query($conn, $sql);
        }
        header("Location: produk-list.php");
        exit();
    }
}
?>

<h3><?= $is_edit ? 'Edit Produk' : 'Tambah Produk Baru'; ?></h3>

<form method="post" enctype="multipart/form-data" class="card p-4 shadow-sm mb-4">
    <input type="hidden" name="gambarLama" value="<?= htmlspecialchars($produk['gambar']); ?>">

    <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama']); ?>" required>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select">
                <option value="Router" <?= $produk['kategori'] === 'Router' ? 'selected' : ''; ?>>Router</option>
                <option value="Kabel UTP" <?= $produk['kategori'] === 'Kabel UTP' ? 'selected' : ''; ?>>Kabel UTP</option>
                <option value="Switch" <?= $produk['kategori'] === 'Switch' ? 'selected' : ''; ?>>Switch</option>
                <option value="Access Point" <?= $produk['kategori'] === 'Access Point' ? 'selected' : ''; ?>>Access Point</option>
                <option value="Aksesori" <?= $produk['kategori'] === 'Aksesori' ? 'selected' : ''; ?>>Aksesori</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Brand</label>
            <input type="text" name="brand" class="form-control" value="<?= htmlspecialchars($produk['brand']); ?>" required>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <label class="form-label">Harga (Rp)</label>
            <input type="number" name="harga" class="form-control" value="<?= $produk['harga']; ?>" required>
        </div>
        <div class="col-md-6 mb-3">
            <label class="form-label">Stok</label>
            <input type="number" name="stok" class="form-control" value="<?= $produk['stok']; ?>" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($produk['deskripsi']); ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Gambar Produk</label>
        <?php if (!empty($produk['gambar']) && file_exists(__DIR__ . '/../assets/img/' . $produk['gambar'])): ?>
            <div class="mb-2">
                <img src="<?= $base_url; ?>assets/img/<?= htmlspecialchars($produk['gambar']); ?>" width="120" class="img-thumbnail">
            </div>
        <?php endif; ?>
        <input type="file" name="gambar" class="form-control" accept="image/*">
        <small class="text-muted">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB). Biarkan kosong jika tidak ingin mengubah gambar.</small>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="produk-list.php" class="btn btn-secondary">Batal</a>
    </div>
</form>

<?php include __DIR__ . '/../components/footer.php'; ?>