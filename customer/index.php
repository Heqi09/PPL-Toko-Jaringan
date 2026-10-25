
<?php
require_once __DIR__ . '/../function.php';
include __DIR__ . '/../components/header.php';

// Mengambil parameter pencarian dan kategori
$keyword = trim($_GET['keyword'] ?? '');
$kategori = $_GET['kategori'] ?? '';

// Mengambil produk dari database
$produk = get_katalog($keyword, $kategori);

// Lokasi folder gambar di server
$folder_gambar = __DIR__ . '/../assets/img/';

// URL folder gambar pada localhost
$url_gambar = '/toko-jaringan/assets/img/';

// Gambar cadangan
$gambar_default = $url_gambar . 'default.jpg';
?>

<div class="mb-4">
    <h2>Katalog Produk Jaringan</h2>
    <p class="text-muted">
        Temukan peralatan jaringan terbaik untuk kebutuhan UMKM Anda.
    </p>
</div>

<!-- Form Cari & Filter -->
<form method="get" class="row g-2 mb-4">

    <div class="col-md-5">
        <input
            type="text"
            name="keyword"
            class="form-control"
            placeholder="Cari nama produk atau brand..."
            value="<?= htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8'); ?>"
        >
    </div>

    <div class="col-md-4">
        <select name="kategori" class="form-select">

            <option value="">Semua Kategori</option>

            <option value="Router"
                <?= $kategori === 'Router' ? 'selected' : ''; ?>>
                Router
            </option>

            <option value="Kabel UTP"
                <?= $kategori === 'Kabel UTP' ? 'selected' : ''; ?>>
                Kabel UTP
            </option>

            <option value="Switch"
                <?= $kategori === 'Switch' ? 'selected' : ''; ?>>
                Switch
            </option>

            <option value="Access Point"
                <?= $kategori === 'Access Point' ? 'selected' : ''; ?>>
                Access Point
            </option>

            <option value="Aksesori"
                <?= $kategori === 'Aksesori' ? 'selected' : ''; ?>>
                Aksesori
            </option>

        </select>
    </div>

    <div class="col-md-3">
        <button
            type="submit"
            class="btn btn-primary w-100">
            Filter
        </button>
    </div>

</form>

<!-- Daftar Produk -->
<div class="row">

    <?php if (empty($produk)): ?>

        <div class="col-12 text-center py-5">
            <p class="text-muted fs-5">
                Produk tidak ditemukan.
            </p>
        </div>

    <?php else: ?>

        <?php foreach ($produk as $p): ?>

            <?php
            // Mengambil nama gambar dari database
            $nama_gambar = basename(
                (string)($p['gambar'] ?? '')
            );

            // Membentuk lokasi gambar di server
            $lokasi_file = $folder_gambar . $nama_gambar;

            // Memeriksa apakah gambar benar-benar tersedia
            if (
                $nama_gambar !== '' &&
                is_file($lokasi_file)
            ) {

                // Menggunakan gambar sesuai database
                $gambar_produk = $url_gambar .
                    rawurlencode($nama_gambar);

            } else {

                // Menggunakan gambar default jika tidak ada
                $gambar_produk = $gambar_default;

            }
            ?>

            <div class="col-md-4 mb-4">

                <div class="card h-100 shadow-sm">

                    <!-- GAMBAR PRODUK -->
<img
    src="<?= htmlspecialchars($gambar_produk, ENT_QUOTES, 'UTF-8'); ?>"
    class="card-img-top p-2"
    alt="<?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8'); ?>"
    style="
        height: 500px;
        width: 100%;
        object-fit: contain;
        background-color: #ffffff;
    "
    loading="lazy"
>

                    <!-- INFORMASI PRODUK -->
                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">
                            <?= htmlspecialchars($p['nama'], ENT_QUOTES, 'UTF-8'); ?>
                        </h5>

                        <h6 class="card-subtitle mb-2 text-muted">
                            <?= htmlspecialchars($p['kategori'], ENT_QUOTES, 'UTF-8'); ?>
                            |
                            <?= htmlspecialchars($p['brand'], ENT_QUOTES, 'UTF-8'); ?>
                        </h6>

                        <!-- HARGA -->
                        <p class="card-text fw-bold text-primary fs-5 mt-auto">
                            Rp <?= number_format(
                                (float)$p['harga'],
                                0,
                                ',',
                                '.'
                            ); ?>
                        </p>

                        <!-- TOMBOL PRODUK -->
                        <div class="d-flex justify-content-between align-items-center mt-3">

                            <a
                                href="detail-produk.php?id=<?= (int)$p['id']; ?>"
                                class="btn btn-outline-secondary btn-sm">
                                Detail
                            </a>

                            <a
                                href="keranjang.php?action=add&id=<?= (int)$p['id']; ?>"
                                class="btn btn-success btn-sm">
                                Tambah ke Keranjang
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php include __DIR__ . '/../components/footer.php'; ?>
