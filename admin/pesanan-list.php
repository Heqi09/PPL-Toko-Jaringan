<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

// Aksi Update Resi & Status Pengiriman
if (isset($_POST['kirim_pesanan'])) {
    $ord_id  = mysqli_real_escape_string($conn, $_POST['order_id']);
    $no_resi = mysqli_real_escape_string($conn, $_POST['no_resi']);
    
    mysqli_query($conn, "UPDATE orders SET status = 'dikirim', no_resi = '$no_resi' WHERE order_id = '$ord_id'");
    echo "<script>alert('Nomor resi berhasil diinput. Status pesanan diubah ke DIKIRIM.'); window.location='pesanan-list.php';</script>";
    exit();
}

if (isset($_POST['update_status_manual'])) {
    update_order_status($_POST['order_id'], $_POST['status']);
    header("Location: pesanan-list.php");
    exit();
}

$orders = query("SELECT o.*, u.nama as nama_pembeli FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC");
?>

<h3>Manajemen Pengiriman & Pesanan (Admin)</h3>

<table class="table table-bordered align-middle mt-3">
    <thead class="table-light">
        <tr>
            <th>Order ID</th>
            <th>Pembeli</th>
            <th>Ekspedisi</th>
            <th>Total Tagihan</th>
            <th>Status</th>
            <th>No. Resi</th>
            <th>Aksi Admin</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $o): ?>
        <tr>
            <td><strong><?= $o['order_id']; ?></strong></td>
            <td><?= htmlspecialchars($o['nama_pembeli']); ?></td>
            <td><span class="badge bg-secondary"><?= $o['kurir'] ?: 'JNE'; ?></span></td>
            <td>Rp <?= number_format($o['total_harga'], 0, ',', '.'); ?></td>
            <td>
                <?php if ($o['status'] === 'dibayar'): ?>
                    <span class="badge bg-success">Dibayar</span>
                <?php elseif ($o['status'] === 'dikirim'): ?>
                    <span class="badge bg-primary">Dikirim</span>
                <?php elseif ($o['status'] === 'selesai'): ?>
                    <span class="badge bg-info text-dark">Selesai</span>
                <?php else: ?>
                    <span class="badge bg-warning text-dark"><?= ucfirst($o['status']); ?></span>
                <?php endif; ?>
            </td>
            <td><?= $o['no_resi'] ? '<code>' . htmlspecialchars($o['no_resi']) . '</code>' : '-'; ?></td>
            <td>
                <?php if ($o['status'] === 'dibayar'): ?>
                    <!-- Form Input Resi -->
                    <form method="post" class="d-flex gap-1">
                        <input type="hidden" name="order_id" value="<?= $o['order_id']; ?>">
                        <input type="text" name="no_resi" class="form-control form-control-sm" placeholder="Input No Resi" required>
                        <button type="submit" name="kirim_pesanan" class="btn btn-sm btn-primary">Kirim</button>
                    </form>
                <?php else: ?>
                    <form method="post" class="d-flex gap-1">
                        <input type="hidden" name="order_id" value="<?= $o['order_id']; ?>">
                        <select name="status" class="form-select form-select-sm">
                            <option value="pending" <?= $o['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="dibayar" <?= $o['status'] === 'dibayar' ? 'selected' : ''; ?>>Dibayar</option>
                            <option value="dikirim" <?= $o['status'] === 'dikirim' ? 'selected' : ''; ?>>Dikirim</option>
                            <option value="selesai" <?= $o['status'] === 'selesai' ? 'selected' : ''; ?>>Selesai</option>
                            <option value="batal" <?= $o['status'] === 'batal' ? 'selected' : ''; ?>>Batal</option>
                        </select>
                        <button type="submit" name="update_status_manual" class="btn btn-sm btn-outline-secondary">Ubah</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../components/footer.php'; ?>