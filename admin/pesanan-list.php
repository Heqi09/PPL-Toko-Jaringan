<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

if (isset($_POST['update_status'])) {
    update_order_status($_POST['order_id'], $_POST['status']);
    header("Location: pesanan-list.php");
    exit();
}

$orders = query("SELECT o.*, u.nama as nama_pembeli FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
?>

<h3>Manajemen Pesanan Masuk (Admin)</h3>

<table class="table table-bordered align-middle mt-3">
    <thead class="table-light">
        <tr>
            <th>Order ID</th>
            <th>Pembeli</th>
            <th>Total</th>
            <th>Alamat Pengiriman</th>
            <th>Status saat ini</th>
            <th>Ubah Status</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><strong><?= $o['order_id']; ?></strong></td>
            <td><?= htmlspecialchars($o['nama_pembeli']); ?></td>
            <td>Rp <?= number_format($o['total_harga'], 0, ',', '.'); ?></td>
            <td><?= htmlspecialchars($o['alamat_pengiriman']); ?></td>
            <td>
                <?php if ($o['status'] === 'dibayar'): ?>
                    <span class="badge bg-success">Dibayar</span>
                <?php elseif ($o['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php else: ?>
                    <span class="badge bg-danger"><?= ucfirst($o['status']); ?></span>
                <?php endif; ?>
            </td>
            <td>
                <form method="post" class="d-flex gap-1">
                    <input type="hidden" name="order_id" value="<?= $o['order_id']; ?>">
                    <select name="status" class="form-select form-select-sm">
                        <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="dibayar" <?= $o['status'] === 'dibayar' ? 'selected' : ''; ?>>Dibayar</option>
                        <option value="batal" <?= $o['status'] === 'batal' ? 'selected' : ''; ?>>Batal</option>
                    </select>
                    <button type="submit" name="update_status" class="btn btn-sm btn-primary">Simpan</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../components/footer.php'; ?>