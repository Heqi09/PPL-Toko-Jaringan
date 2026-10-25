<?php
require_once __DIR__ . '/../function.php';
check_auth('customer');
include __DIR__ . '/../components/header.php';

$user_id = $_SESSION['user']['id'];

// Tombol Simulasi Lunas untuk keperluan testing lokal / Monev
if (isset($_GET['action']) && $_GET['action'] === 'pay_simulated') {
    update_order_status($_GET['order_id'], 'dibayar');
    header("Location: pesanan.php");
    exit();
}

$orders = query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
?>

<script type="text/javascript" src="<?= MIDTRANS_SNAP_JS_URL; ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY; ?>"></script>

<h3>Riwayat Pesanan Saya</h3>

<?php if (empty($orders)): ?>
    <div class="alert alert-info py-4 text-center mt-3">Belum ada transaksi. <a href="index.php">Mulai Belanja</a></div>
<?php else: ?>
    <table class="table table-bordered align-middle mt-3">
        <thead class="table-light">
            <tr>
                <th>ID Pesanan</th>
                <th>Tanggal</th>
                <th>Total Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $o): ?>
            <tr>
                <td><strong><?= $o['order_id']; ?></strong></td>
                <td><?= date('d M Y H:i', strtotime($o['created_at'])); ?></td>
                <td>Rp <?= number_format($o['total_harga'], 0, ',', '.'); ?></td>
                <td>
                    <?php if ($o['status'] === 'dibayar'): ?>
                        <span class="badge bg-success">Dibayar</span>
                    <?php elseif ($o['status'] === 'pending'): ?>
                        <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                    <?php else: ?>
                        <span class="badge bg-danger"><?= ucfirst($o['status']); ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($o['status'] === 'pending'): ?>
                        <button onclick="snap.pay('<?= $o['snap_token']; ?>')" class="btn btn-primary btn-sm">Bayar Via Midtrans</button>
                        <a href="pesanan.php?action=pay_simulated&order_id=<?= $o['order_id']; ?>" class="btn btn-outline-success btn-sm ms-1" onclick="return confirm('Simulasikan status LUNAS?')">Simulasi Lunas (Sandbox)</a>
                    <?php else: ?>
                        <button class="btn btn-secondary btn-sm" disabled>Selesai</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>