<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/config_midtrans.php';
require_once __DIR__ . '/config_ongkir.php';

function query($sql) {
    global $conn;
    $result = mysqli_query($conn, $sql);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fitur Auth
function registrasi($data) {
    global $conn;
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $username = strtolower(mysqli_real_escape_string($conn, $data['username']));
    $password = mysqli_real_escape_string($conn, $data['password']);
    
    $check = query("SELECT username FROM users WHERE username = '$username'");
    if (!empty($check)) {
        return false;
    }
    
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (nama, username, password, role) VALUES ('$nama', '$username', '$password_hash', 'customer')";
    return mysqli_query($conn, $sql);
}

function login($username, $password) {
    global $conn;
    $username = mysqli_real_escape_string($conn, $username);
    $user = query("SELECT * FROM users WHERE username = '$username'");
    
    if (!empty($user)) {
        if (password_verify($password, $user[0]['password'])) {
            return $user[0];
        }
    }
    return false;
}

// Fitur Katalog, Cari & Filter
function get_katalog($keyword = '', $kategori = '') {
    $sql = "SELECT * FROM produk WHERE 1=1";
    if (!empty($keyword)) {
        $sql .= " AND (nama LIKE '%$keyword%' OR brand LIKE '%$keyword%')";
    }
    if (!empty($kategori)) {
        $sql .= " AND kategori = '$kategori'";
    }
    $sql .= " ORDER BY id DESC";
    return query($sql);
}

// Fitur CRUD Produk (Admin)
function tambah_produk($data) {
    global $conn;
    $nama = mysqli_real_escape_string($conn, $data['nama']);
    $kategori = mysqli_real_escape_string($conn, $data['kategori']);
    $harga = intval($data['harga']);
    $stok = intval($data['stok']);
    $brand = mysqli_real_escape_string($conn, $data['brand']);
    $deskripsi = mysqli_real_escape_string($conn, $data['deskripsi']);

    $sql = "INSERT INTO produk (nama, kategori, harga, stok, brand, deskripsi) 
            VALUES ('$nama', '$kategori', $harga, $stok, '$brand', '$deskripsi')";
    return mysqli_query($conn, $sql);
}

function hapus_produk($id) {
    global $conn;
    $id = intval($id);
    return mysqli_query($conn, "DELETE FROM produk WHERE id = $id");
}

// Fitur Keranjang (Customer)
function tambah_keranjang($user_id, $produk_id, $qty = 1) {
    global $conn;

    $user_id   = intval($user_id);
    $produk_id = intval($produk_id);
    $qty       = intval($qty);

    // 1. Cek apakah user benar-benar ada di database
    $cek_user = query("SELECT id FROM users WHERE id = $user_id");
    if (empty($cek_user)) {
        // Jika user tidak ditemukan, bersihkan session dan arahkan ke login
        session_destroy();
        echo "<script>alert('Sesi login tidak valid. Silakan login kembali.'); window.location='../login.php';</script>";
        exit();
    }

    // 2. Cek apakah produk sudah ada di keranjang user
    $cek_keranjang = query("SELECT id, qty FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");

    if (!empty($cek_keranjang)) {
        // Jika sudah ada, tambahkan jumlahnya (qty)
        $qty_baru = $cek_keranjang[0]['qty'] + $qty;
        $cart_id  = $cek_keranjang[0]['id'];
        return mysqli_query($conn, "UPDATE keranjang SET qty = $qty_baru WHERE id = $cart_id");
    } else {
        // Jika belum ada, insert baru
        return mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, qty) VALUES ($user_id, $produk_id, $qty)");
    }
}

// Fungsi untuk membatalkan pesanan pending & mengembalikan stok produk
function batalkan_pesanan($order_id, $user_id) {
    global $conn;
    $order_id = mysqli_real_escape_string($conn, $order_id);

    // Cek apakah pesanan memang ada, milik user bersangkutan, dan berstatus pending
    $order = query("SELECT * FROM orders WHERE order_id = '$order_id' AND user_id = $user_id AND status = 'pending'");
    
    if (!empty($order)) {
        // 1. Kembalikan stok produk yang pernah dipesan
        $details = query("SELECT produk_id, qty FROM order_details WHERE order_id = '$order_id'");
        foreach ($details as $d) {
            $p_id = $d['produk_id'];
            $qty  = $d['qty'];
            mysqli_query($conn, "UPDATE produk SET stok = stok + $qty WHERE id = $p_id");
        }
        
        // 2. Ubah status pesanan menjadi batal
        mysqli_query($conn, "UPDATE orders SET status = 'batal' WHERE order_id = '$order_id'");
        return true;
    }
    return false;
}

function upload_gambar() {
    $namaFile   = $_FILES['gambar']['name'];
    $ukuranFile = $_FILES['gambar']['size'];
    $error      = $_FILES['gambar']['error'];
    $tmpName    = $_FILES['gambar']['tmp_name'];

    // Jika tidak ada gambar yang diunggah
    if ($error === 4) {
        return 'default.jpg';
    }

    // Validasi ekstensi gambar
    $ekstensiGambarValid = ['jpg', 'jpeg', 'png', 'webp'];
    $ekstensiGambar = explode('.', $namaFile);
    $ekstensiGambar = strtolower(end($ekstensiGambar));

    if (!in_array($ekstensiGambar, $ekstensiGambarValid)) {
        echo "<script>alert('Format file harus JPG, JPEG, PNG, atau WEBP!');</script>";
        return false;
    }

    // Validasi ukuran (maksimal 2MB)
    if ($ukuranFile > 2000000) {
        echo "<script>alert('Ukuran gambar terlalu besar! Maksimal 2MB');</script>";
        return false;
    }

    // Generate nama unik untuk mencegah duplikasi nama file
    $namaFileBaru = uniqid() . '.' . $ekstensiGambar;
    $tujuan = __DIR__ . '/assets/img/' . $namaFileBaru;

    if (move_uploaded_file($tmpName, $tujuan)) {
        return $namaFileBaru;
    }

    return false;
}

// Request Snap Token ke Server Midtrans
function get_midtrans_snap_token($order_id, $gross_amount, $customer_name, $items = []) {
    $item_details = [];
    foreach ($items as $it) {
        // Menggunakan null coalescing operator (??) agar mendukung key 'id'/'produk_id', 'price'/'harga', dst.
        $id    = $it['id'] ?? $it['produk_id'] ?? 'ITEM';
        $price = (int)($it['price'] ?? $it['harga'] ?? 0);
        $qty   = (int)($it['quantity'] ?? $it['qty'] ?? 1);
        $nama  = $it['name'] ?? $it['nama'] ?? 'Produk';
        
        $item_details[] = [
            'id'       => (string)$id,
            'price'    => $price,
            'quantity' => $qty,
            'name'     => substr((string)$nama, 0, 50)
        ];
    }

    $transaction_details = [
        'order_id'     => $order_id,
        'gross_amount' => (int)$gross_amount,
    ];

    $customer_details = [
        'first_name' => $customer_name,
    ];

    $params = [
        'transaction_details' => $transaction_details,
        'customer_details'    => $customer_details,
        'item_details'        => $item_details,
    ];

    $auth = base64_encode(MIDTRANS_SERVER_KEY . ':');

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL            => MIDTRANS_SNAP_URL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => json_encode($params),
        CURLOPT_HTTPHEADER     => [
            'Accept: application/json',
            'Content-Type: application/json',
            'Authorization: Basic ' . $auth
        ],
    ]);

    $response = curl_exec($curl);
    curl_close($curl);

    $result = json_decode($response, true);
    return $result['token'] ?? '';
}

// Proses Pembuatan Order & Token Semua Pembayaran Pembayaran
function proses_checkout_pesanan_v2($user_id, $alamat, $kota_tujuan, $kurir, $catatan) {
    global $conn;

    $cart_items = query("SELECT k.qty, k.produk_id, p.harga, p.nama, p.stok, p.berat 
                         FROM keranjang k JOIN produk p ON k.produk_id = p.id 
                         WHERE k.user_id = $user_id");

    if (empty($cart_items)) {
        return ['status' => false, 'message' => 'Keranjang Anda kosong.'];
    }

    $total_harga_barang = 0;
    $total_berat_gram   = 0;

    foreach ($cart_items as $item) {
        if ($item['qty'] > $item['stok']) {
            return ['status' => false, 'message' => "Stok produk '" . $item['nama'] . "' tidak mencukupi."];
        }
        $total_harga_barang += ($item['harga'] * $item['qty']);
        $berat_item          = (!empty($item['berat']) && $item['berat'] > 0) ? $item['berat'] : 500;
        $total_berat_gram   += ($berat_item * $item['qty']);
    }

    // Hitung Ongkir
    $ongkir                  = hitung_ongkos_kirim($kota_tujuan, $total_berat_gram, $kurir);
    $total_bayar_keseluruhan = $total_harga_barang + $ongkir;

    $order_id      = 'ORD-' . time() . '-' . $user_id;
    $user_name     = $_SESSION['user']['nama'] ?? 'Customer';
    $alamat_clean  = mysqli_real_escape_string($conn, $alamat);
    $kota_clean    = mysqli_real_escape_string($conn, $kota_tujuan);
    $kurir_clean   = mysqli_real_escape_string($conn, strtoupper($kurir));
    $catatan_clean = mysqli_real_escape_string($conn, $catatan);

    // Payload Item
    $items_payload = $cart_items;
    $items_payload[] = [
        'produk_id' => 'ONGKIR',
        'harga'     => $ongkir,
        'qty'       => 1,
        'nama'      => "Ongkos Kirim ($kurir_clean)"
    ];

    $snap_token = get_midtrans_snap_token($order_id, $total_bayar_keseluruhan, $user_name, $items_payload);

    $sql_order = "INSERT INTO orders (order_id, user_id, total_harga, total_berat, ongkir, status, snap_token, alamat_pengiriman, kota_tujuan, kurir, catatan) 
                  VALUES ('$order_id', $user_id, $total_bayar_keseluruhan, $total_berat_gram, $ongkir, 'pending', '$snap_token', '$alamat_clean', '$kota_clean', '$kurir_clean', '$catatan_clean')";
    
    if (mysqli_query($conn, $sql_order)) {
        foreach ($cart_items as $item) {
            $p_id     = $item['produk_id'];
            $p_nama   = mysqli_real_escape_string($conn, $item['nama']);
            $p_harga  = $item['harga'];
            $p_qty    = $item['qty'];
            $subtotal = $p_harga * $p_qty;

            mysqli_query($conn, "INSERT INTO order_details (order_id, produk_id, nama_produk, harga, qty, subtotal) 
                                 VALUES ('$order_id', $p_id, '$p_nama', $p_harga, $p_qty, $subtotal)");
            
            // Potong stok
            mysqli_query($conn, "UPDATE produk SET stok = stok - $p_qty WHERE id = $p_id");
        }

        // Hapus item keranjang
        mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $user_id");

        return ['status' => true, 'order_id' => $order_id, 'snap_token' => $snap_token];
    }

    return ['status' => false, 'message' => 'Gagal memproses pesanan.'];
}

// Komponen Komputasional: Simple Moving Average (SMA) 3 Periode Perkiraan Kebutuhan Stok
function hitung_forecast_moving_average_3_periode($produk_id) {
    global $conn;
    $produk_id = intval($produk_id);

    // Ambil histori total penjualan produk 3 bulan/periode terakhir
    $query = "SELECT SUM(od.qty) as total_qty, DATE_FORMAT(o.created_at, '%Y-%m') as periode
              FROM order_details od
              JOIN orders o ON od.order_id = o.order_id
              WHERE od.produk_id = $produk_id AND o.status IN ('dibayar', 'dikirim', 'selesai')
              GROUP BY DATE_FORMAT(o.created_at, '%Y-%m')
              ORDER BY periode DESC LIMIT 3";

    $histori = query($query);
    $total_histori = count($histori);

    if ($total_histori === 0) {
        return ['forecast' => 0, 'detail' => 'Belum ada data penjualan'];
    }

    $sum_qty = 0;
    foreach ($histori as $h) {
        $sum_qty += $h['total_qty'];
    }

    // Rumus SMA = (P1 + P2 + P3) / N
    $forecast = ceil($sum_qty / $total_histori);

    return [
        'forecast'      => $forecast,
        'periode_count' => $total_histori,
        'detail'        => "Rata-rata dari $total_histori periode penjualan terakhir"
    ];
}

function update_order_status($order_id, $status) {
    global $conn;
    $order_id = mysqli_real_escape_string($conn, $order_id);
    $status   = mysqli_real_escape_string($conn, $status);
    return mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'");
}

// 1. Komponen Komputasional Rule-Based: Peringatan Restock (Stok < 5)
function get_peringatan_restock() {
    return query("SELECT * FROM produk WHERE stok < 5 ORDER BY stok ASC");
}

// 2. Komponen Komputasional Scoring Sederhana: Top 5 Produk Terlaris
function get_top_5_terlaris() {
    return query("SELECT p.nama, p.kategori, SUM(od.qty) as total_terjual 
                  FROM order_details od 
                  JOIN orders o ON od.order_id = o.order_id 
                  JOIN produk p ON od.produk_id = p.id 
                  WHERE o.status = 'dibayar' 
                  GROUP BY od.produk_id 
                  ORDER BY total_terjual DESC LIMIT 5");
}
?>
