<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/config_midtrans.php';

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
    $check = query("SELECT * FROM keranjang WHERE user_id = $user_id AND produk_id = $produk_id");
    if (!empty($check)) {
        $new_qty = $check[0]['qty'] + $qty;
        return mysqli_query($conn, "UPDATE keranjang SET qty = $new_qty WHERE id = " . $check[0]['id']);
    } else {
        return mysqli_query($conn, "INSERT INTO keranjang (user_id, produk_id, qty) VALUES ($user_id, $produk_id, $qty)");
    }
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
function get_midtrans_snap_token($order_id, $gross_amount, $user_name, $items) {
    $item_details = [];
    foreach ($items as $item) {
        $item_details[] = [
            'id'       => $item['produk_id'],
            'price'    => (int)$item['harga'],
            'quantity' => (int)$item['qty'],
            'name'     => substr($item['nama'], 0, 50)
        ];
    }

    $payload = [
        'transaction_details' => [
            'order_id'     => $order_id,
            'gross_amount' => (int)$gross_amount
        ],
        'customer_details' => [
            'first_name' => $user_name
        ],
        'item_details' => $item_details
    ];

    $ch = curl_init(MIDTRANS_SNAP_URL);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Basic ' . base64_encode(MIDTRANS_SERVER_KEY . ':')
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    return $result['token'] ?? null;
}

// Proses Pembuatan Order & Token Pembayaran
function proses_checkout_pesanan($user_id, $alamat, $catatan) {
    global $conn;

    $cart_items = query("SELECT k.qty, k.produk_id, p.harga, p.nama, p.stok 
                         FROM keranjang k JOIN produk p ON k.produk_id = p.id 
                         WHERE k.user_id = $user_id");

    if (empty($cart_items)) {
        return ['status' => false, 'message' => 'Keranjang Anda kosong.'];
    }

    $total_harga = 0;
    foreach ($cart_items as $item) {
        if ($item['qty'] > $item['stok']) {
            return ['status' => false, 'message' => "Stok produk '" . $item['nama'] . "' tidak mencukupi."];
        }
        $total_harga += ($item['harga'] * $item['qty']);
    }

    $order_id     = 'ORD-' . time() . '-' . $user_id;
    $user_name    = $_SESSION['user']['nama'] ?? 'Customer';
    $alamat_clean = mysqli_real_escape_string($conn, $alamat);
    $catatan_clean= mysqli_real_escape_string($conn, $catatan);

    $snap_token = get_midtrans_snap_token($order_id, $total_harga, $user_name, $cart_items);

    $sql_order = "INSERT INTO orders (order_id, user_id, total_harga, status, snap_token, alamat_pengiriman, catatan) 
                  VALUES ('$order_id', $user_id, $total_harga, 'pending', '$snap_token', '$alamat_clean', '$catatan_clean')";
    
    if (mysqli_query($conn, $sql_order)) {
        foreach ($cart_items as $item) {
            $p_id    = $item['produk_id'];
            $p_nama  = mysqli_real_escape_string($conn, $item['nama']);
            $p_harga = $item['harga'];
            $p_qty   = $item['qty'];
            $subtotal= $p_harga * $p_qty;

            mysqli_query($conn, "INSERT INTO order_details (order_id, produk_id, nama_produk, harga, qty, subtotal) 
                                 VALUES ('$order_id', $p_id, '$p_nama', $p_harga, $p_qty, $subtotal)");
            
            // Otomatis kurangi stok produk
            mysqli_query($conn, "UPDATE produk SET stok = stok - $p_qty WHERE id = $p_id");
        }

        mysqli_query($conn, "DELETE FROM keranjang WHERE user_id = $user_id");

        return ['status' => true, 'order_id' => $order_id, 'snap_token' => $snap_token];
    }

    return ['status' => false, 'message' => 'Gagal membuat pesanan.'];
}

function update_order_status($order_id, $status) {
    global $conn;
    $order_id = mysqli_real_escape_string($conn, $order_id);
    $status   = mysqli_real_escape_string($conn, $status);
    return mysqli_query($conn, "UPDATE orders SET status = '$status' WHERE order_id = '$order_id'");
}

// Komponen Komputasional Rule-Based: Peringatan Restock (Stok < 5)
function get_peringatan_restock() {
    return query("SELECT * FROM produk WHERE stok < 5 ORDER BY stok ASC");
}

// Komponen Komputasional Scoring Sederhana: Top 5 Produk Terlaris
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
