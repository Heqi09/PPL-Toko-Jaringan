<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/conn.php';
require_once __DIR__ . '/conn.php';

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
?>