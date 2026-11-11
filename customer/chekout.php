<?php
require_once __DIR__ . '/../function.php';
check_auth('customer');
include __DIR__ . '/../components/header.php';

$user_id = $_SESSION['user']['id'];
$cart_items = query("SELECT k.qty, p.* FROM keranjang k JOIN produk p ON k.produk_id = p.id WHERE k.user_id = $user_id");

if (empty($cart_items)) {
    echo "<script>alert('Keranjang kosong!'); window.location='index.php';</script>";
    exit();
}

$subtotal_barang = 0;
$total_berat = 0;
foreach ($cart_items as $item) {
    $subtotal_barang += ($item['harga'] * $item['qty']);
    $total_berat += (($item['berat'] ?? 500) * $item['qty']);
}

$checkout_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat  = $_POST['alamat_pengiriman'] ?? '';
    $kota    = $_POST['kota_tujuan'] ?? 'Surabaya';
    $kurir   = $_POST['kurir'] ?? 'JNE';
    $catatan = $_POST['catatan'] ?? '';

    $checkout_result = proses_checkout_pesanan_v2($user_id, $alamat, $kota, $kurir, $catatan);
}
?>

<script type="text/javascript" src="<?= MIDTRANS_SNAP_JS_URL; ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY; ?>"></script>

<h3>Checkout & Pengiriman</h3>

<form method="post" id="formCheckout">
<div class="row mt-3">
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Informasi Pengiriman</h5>
            <div class="mb-3">
                <label class="form-label">Alamat Lengkap Pengiriman</label>
                <textarea name="alamat_pengiriman" class="form-control" rows="3" required placeholder="Jl. Raya Telang No. 12, Kamal, Bangkalan"><?= htmlspecialchars($_POST['alamat_pengiriman'] ?? ''); ?></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Kota Tujuan</label>
                    <select name="kota_tujuan" class="form-select" required>
                        <option value="Bangkalan" <?= (($_POST['kota_tujuan'] ?? '') === 'Bangkalan') ? 'selected' : ''; ?>>Bangkalan</option>
                        <option value="Surabaya" <?= (($_POST['kota_tujuan'] ?? '') === 'Surabaya') ? 'selected' : ''; ?>>Surabaya</option>
                        <option value="Sidoarjo" <?= (($_POST['kota_tujuan'] ?? '') === 'Sidoarjo') ? 'selected' : ''; ?>>Sidoarjo</option>
                        <option value="Malang" <?= (($_POST['kota_tujuan'] ?? '') === 'Malang') ? 'selected' : ''; ?>>Malang</option>
                        <option value="Jakarta" <?= (($_POST['kota_tujuan'] ?? '') === 'Jakarta') ? 'selected' : ''; ?>>Jakarta</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pilih Ekspedisi / Kurir</label>
                    <select name="kurir" class="form-select" required>
                        <option value="JNE" <?= (($_POST['kurir'] ?? '') === 'JNE') ? 'selected' : ''; ?>>JNE Express</option>
                        <option value="POS" <?= (($_POST['kurir'] ?? '') === 'POS') ? 'selected' : ''; ?>>POS Indonesia</option>
                        <option value="TIKI" <?= (($_POST['kurir'] ?? '') === 'TIKI') ? 'selected' : ''; ?>>TIKI</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan Tambahan (Opsional)</label>
                <input type="text" name="catatan" class="form-control" placeholder="Contoh: Titipkan di pos satpam" value="<?= htmlspecialchars($_POST['catatan'] ?? ''); ?>">
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Rincian Tagihan</h5>
            <ul class="list-group list-group-flush mb-3">
                <?php foreach ($cart_items as $item): ?>
                <li class="list-group-item d-flex justify-content-between px-0">
                    <div>
                        <h6 class="my-0"><?= htmlspecialchars($item['nama']); ?></h6>
                        <small class="text-muted"><?= $item['qty']; ?> x Rp <?= number_format($item['harga'], 0, ',', '.'); ?></small>
                    </div>
                    <span>Rp <?= number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?></span>
                </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between px-0 text-muted">
                    <span>Total Berat Estimasi</span>
                    <span><?= number_format($total_berat / 1000, 2, ',', '.'); ?> kg</span>
                </li>
            </ul>
            
            <button type="submit" class="btn btn-success btn-lg w-100">Lanjut ke Pembayaran Sandbox</button>
        </div>
    </div>
</div>
</form>

<?php if ($checkout_result): ?>
    <?php if ($checkout_result['status']): ?>
        <script>
            snap.pay('<?= $checkout_result['snap_token']; ?>', {
                onSuccess: function(result) { window.location.href = "pesanan.php"; },
                onPending: function(result) { window.location.href = "pesanan.php"; },
                onError: function(result) { alert("Pembayaran Gagal!"); window.location.href = "pesanan.php"; },
                onClose: function() { window.location.href = "pesanan.php"; }
            });
        </script>
    <?php else: ?>
        <script>
            alert("Gagal Checkout: <?= addslashes($checkout_result['message']); ?>");
        </script>
    <?php endif; ?>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>
