<?php
require_once __DIR__ . '/../function.php';
check_auth('customer');
include __DIR__ . '/../components/header.php';

$user_id = $_SESSION['user']['id'];
$action = $_GET['action'] ?? '';
$produk_id = intval($_GET['id'] ?? 0);

if ($action === 'add' && $produk_id > 0) {
    tambah_keranjang($user_id, $produk_id, 1);
    header("Location: keranjang.php");
    exit();
}

if ($action === 'delete' && $produk_id > 0) {
    mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");
    header("Location: keranjang.php");
    exit();
}

$cart_items = query("SELECT k.id as cart_id, k.qty, p.* FROM keranjang k JOIN produk p ON k.produk_id = p.id WHERE k.user_id = $user_id");
$total_harga = 0;
?>

<h3>Keranjang Belanja</h3>

<?php if (empty($cart_items)): ?>
    <div class="alert alert-info py-4 text-center mt-3">
        Keranjang belanja Anda masih kosong. <a href="index.php">Belanja Sekarang</a>
    </div>
<?php else: ?>
    <table class="table table-bordered align-middle mt-3">
        <thead class="table-light">
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart_items as $item): 
                $subtotal = $item['harga'] * $item['qty'];
                $total_harga += $subtotal;
            ?>
            <tr>
                <td><?= htmlspecialchars($item['nama']); ?></td>
                <td>Rp <?= number_format($item['harga'], 0, ',', '.'); ?></td>
                <td><?= $item['qty']; ?></td>
                <td>Rp <?= number_format($subtotal, 0, ',', '.'); ?></td>
                <td>
                    <a href="keranjang.php?action=delete&id=<?= $item['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus item ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-end">Total Pembayaran:</th>
                <th>Rp <?= number_format($total_harga, 0, ',', '.'); ?></th>
                <td></td>
            </tr>
        </tfoot>
    </table>
    <div class="d-flex justify-content-between">
        <a href="index.php" class="btn btn-outline-primary">Lanjut Belanja</a>
        <button class="btn btn-success" onclick="alert('Fitur checkout segera hadir!')">Checkout</button>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>