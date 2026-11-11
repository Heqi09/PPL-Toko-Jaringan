<?php
require_once __DIR__ . '/../function.php';
check_auth('admin');
include __DIR__ . '/../components/header.php';

$semua_produk = query("SELECT * FROM produk ORDER BY nama ASC");
?>

<h3>Analisis Komputasional: Perkiraan Stok (Simple Moving Average 3 Periode)</h3>
<p class="text-muted">Fitur Komputasional PPL: Memprediksi kebutuhan restock produk berdasarkan rata-rata penjualan 3 periode terakhir[cite: 10].</p>

<table class="table table-bordered align-middle mt-3">
    <thead class="table-dark">
        <tr>
            <th>Nama Produk</th>
            <th>Stok Saat Ini</th>
            <th>Perkiraan Kebutuhan Periode Depan (SMA)</th>
            <th>Rekomendasi Restock</th>
            <th>Status Keputusan</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($semua_produk as $p): 
            $ma = hitung_forecast_moving_average_3_periode($p['id']);
            $estimasi = $ma['forecast'];
            $stok_sekarang = $p['stok'];
            $butuh_restock = ($stok_sekarang < $estimasi);
        ?>
        <tr>
            <td><strong><?= htmlspecialchars($p['nama']); ?></strong></td>
            <td><?= $stok_sekarang; ?> unit</td>
            <td><span class="badge bg-primary fs-6"><?= $estimasi; ?> unit</span></td>
            <td><?= max(0, $estimasi - $stok_sekarang); ?> unit tambahan</td>
            <td>
                <?php if ($butuh_restock): ?>
                    <span class="badge bg-danger">⚠️ Segera Restock</span>
                <?php else: ?>
                    <span class="badge bg-success">✅ Stok Cukup</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include __DIR__ . '/../components/footer.php'; ?>