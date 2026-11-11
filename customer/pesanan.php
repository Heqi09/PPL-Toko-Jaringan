<?php
require_once __DIR__ . '/../function.php';
check_auth('customer');
include __DIR__ . '/../components/header.php';

$user_id = $_SESSION['user']['id'];

// 1. Handling Aksi Batal Pesanan (Mengembalikan Stok)
if (isset($_GET['action']) && $_GET['action'] === 'cancel') {
    $ord_id = $_GET['order_id'] ?? '';
    if (batalkan_pesanan($ord_id, $user_id)) {
        echo "<script>alert('Pesanan berhasil dibatalkan dan stok produk telah dikembalikan.'); window.location='pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal membatalkan pesanan.'); window.location='pesanan.php';</script>";
    }
    exit();
}

// 2. Handling Simulasi Lunas Sandbox (Testing Mode)
if (isset($_GET['action']) && $_GET['action'] === 'pay_simulated') {
    update_order_status($_GET['order_id'], 'dibayar');
    header("Location: pesanan.php");
    exit();
}

// 3. Handling Customer Klik "Konfirmasi Terima Barang"
if (isset($_GET['action']) && $_GET['action'] === 'confirm_received') {
    $ord_id = mysqli_real_escape_string($conn, $_GET['order_id']);
    mysqli_query($conn, "UPDATE orders SET status = 'selesai' WHERE order_id = '$ord_id' AND user_id = $user_id");
    echo "<script>alert('Terima kasih! Pesanan Anda telah dikonfirmasi SELESAI.'); window.location='pesanan.php';</script>";
    exit();
}

$orders = query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
?>

<script type="text/javascript" src="<?= MIDTRANS_SNAP_JS_URL; ?>" data-client-key="<?= MIDTRANS_CLIENT_KEY; ?>"></script>

<h3>Riwayat & Lacak Pesanan Saya</h3>

<?php if (empty($orders)): ?>
    <div class="alert alert-info py-4 text-center mt-3">
        Belum ada riwayat pesanan. <a href="index.php">Mulai Belanja</a>
    </div>
<?php else: ?>
    <div class="table-responsive mt-3">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID Pesanan</th>
                    <th>Tanggal</th>
                    <th>Total Pembayaran</th>
                    <th>No. Resi Pengiriman</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $o): 
                    $ord_id = $o['order_id'];
                    $items = query("SELECT * FROM order_details WHERE order_id = '$ord_id'");
                ?>
                <tr>
                    <td><strong><?= $o['order_id']; ?></strong></td>
                    <td><?= date('d M Y H:i', strtotime($o['created_at'])); ?></td>
                    <td>Rp <?= number_format($o['total_harga'], 0, ',', '.'); ?></td>
                    <td>
                        <?php if (!empty($o['no_resi'])): ?>
                            <span class="fw-bold text-primary"><?= htmlspecialchars($o['no_resi']); ?></span>
                            <br><small class="text-muted">(Kurir: <?= htmlspecialchars($o['kurir'] ?: 'JNE'); ?>)</small>
                        <?php else: ?>
                            <small class="text-muted">Menunggu nomor resi...</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($o['status'] === 'selesai'): ?>
                            <span class="badge bg-info text-dark">Selesai</span>
                        <?php elseif ($o['status'] === 'dikirim'): ?>
                            <span class="badge bg-primary">Dalam Pengiriman</span>
                        <?php elseif ($o['status'] === 'dibayar'): ?>
                            <span class="badge bg-success">Dibayar (Diproses)</span>
                        <?php elseif ($o['status'] === 'pending'): ?>
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><?= ucfirst($o['status']); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <!-- Tombol Lihat Detail Pesanan (Modal Pop-up) -->
                            <button type="button" class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#modalDetail<?= $o['id']; ?>">
                                Lihat Detail
                            </button>

                            <?php if ($o['status'] === 'pending'): ?>
                                <!-- Tombol Bayar Midtrans -->
                                <button onclick="snap.pay('<?= $o['snap_token']; ?>')" class="btn btn-primary btn-sm">Bayar</button>
                                
                                <!-- Tombol Simulasi Lunas Sandbox -->
                                <a href="pesanan.php?action=pay_simulated&order_id=<?= $o['order_id']; ?>" class="btn btn-outline-success btn-sm" onclick="return confirm('Simulasikan status LUNAS?')">Simulasi Lunas</a>
                                
                                <!-- Tombol Batal Pesanan -->
                                <a href="pesanan.php?action=cancel&order_id=<?= $o['order_id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin membatalkan pesanan ini? Stok akan dikembalikan.')">Batal</a>
                            <?php elseif ($o['status'] === 'dikirim'): ?>
                                <!-- Tombol Terima Barang -->
                                <a href="pesanan.php?action=confirm_received&order_id=<?= $o['order_id']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Apakah Anda yakin sudah menerima paket barang ini?')">Konfirmasi Terima Barang</a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-sm" disabled>Tuntas</button>
                            <?php endif; ?>
                        </div>

                        <!-- Modal Detail Pesanan -->
                        <div class="modal fade" id="modalDetail<?= $o['id']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pesanan #<?= $o['order_id']; ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-1"><strong>Alamat Pengiriman:</strong> <?= htmlspecialchars($o['alamat_pengiriman']); ?></p>
                                        <p class="mb-1"><strong>Ekspedisi & Kota Tujuan:</strong> <?= htmlspecialchars($o['kurir'] ?: '-'); ?> (Kota: <?= htmlspecialchars($o['kota_tujuan'] ?: '-'); ?>)</p>
                                        <p class="mb-3"><strong>Catatan:</strong> <?= htmlspecialchars($o['catatan'] ?: '-'); ?></p>
                                        
                                        <h6>Daftar Item Belanja:</h6>
                                        <table class="table table-sm table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Nama Produk</th>
                                                    <th>Harga</th>
                                                    <th>Jumlah</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($items as $it): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($it['nama_produk']); ?></td>
                                                    <td>Rp <?= number_format($it['harga'], 0, ',', '.'); ?></td>
                                                    <td><?= $it['qty']; ?></td>
                                                    <td>Rp <?= number_format($it['subtotal'], 0, ',', '.'); ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <div class="text-end fw-bold fs-5 mt-2">
                                            Total Tagihan: Rp <?= number_format($o['total_harga'], 0, ',', '.'); ?>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->

                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../components/footer.php'; ?>
