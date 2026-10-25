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

$total_pembayaran = 0;
foreach ($cart_items as $item) {
    $total_pembayaran += ($item['harga'] * $item['qty']);
}

$checkout_result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alamat  = $_POST['alamat_pengiriman'] ?? '';
    $catatan = $_POST['catatan'] ?? '';
    $checkout_result = proses_checkout_pesanan($user_id, $alamat, $catatan);
}
?>

<script type="text/javascript" src="<?= MIDTRANS_SNAP_JS_URL; ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY; ?>"></script>

<h3>Form Checkout Pesanan</h3>

<div class="row mt-3">
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Informasi Pengiriman</h5>
            <form method="post">
                <div class="mb-3">
                    <label class="form-label">Alamat Lengkap Pengiriman</label>
                    <textarea name="alamat_pengiriman" class="form-control" rows="3" required placeholder="Jl. Telang No. X, Kamal, Bangkalan"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Catatan Tambahan</label>
                    <input type="text" name="catatan" class="form-control" placeholder="Contoh: Titipkan ke satpam">
                </div>
                <button type="submit" class="btn btn-success btn-lg w-100">Bayar via Midtrans Sandbox</button>
            </form>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card shadow-sm p-4">
            <h5 class="mb-3">Ringkasan Belanja</h5>
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
            </ul>
            <div class="d-flex justify-content-between fs-5 fw-bold">
                <span>Total:</span>
                <span class="text-primary">Rp <?= number_format($total_pembayaran, 0, ',', '.'); ?></span>
            </div>
        </div>
    </div>
</div>

<?php if ($checkout_result && $checkout_result['status']): ?>
<script>
    snap.pay('<?= $checkout_result['snap_token']; ?>', {
        onSuccess: function(result) { window.location.href = "pesanan.php"; },
        onPending: function(result) { window.location.href = "pesanan.php"; },
        onError: function(result) { alert("Pembayaran Gagal!"); window.location.href = "pesanan.php"; },
        onClose: function() { window.location.href = "pesanan.php"; }
    });
</script>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>