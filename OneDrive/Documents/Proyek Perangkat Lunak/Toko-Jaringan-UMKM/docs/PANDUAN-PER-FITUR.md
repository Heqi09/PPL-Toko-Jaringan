# Panduan Pembagian Kode per Fitur — Toko Jaringan (Sprint 1)

Kode dipecah menjadi **8 folder fitur** di `fitur/`, satu folder untuk satu bagian pekerjaan. Setiap anggota tim cukup memasukkan **folder fiturnya sendiri** ke GitHub. Server memuat semua folder fitur otomatis, jadi tidak ada file bersama yang harus diubah dan tidak terjadi bentrok antar anggota.

**Daftar isi**
- Bagian A — Cara menjalankan website (customer dan admin)
- Bagian B — Cara kerja struktur `fitur/` dan pembagian 8 fitur
- Bagian C — Tahap 0: fondasi (dimasukkan lebih dulu)
- Bagian D — Kode per fitur (8 fitur: file, kode, uji, perintah git)
- Bagian E — Alur git untuk anggota tim

---


# BAGIAN A — CARA MENJALANKAN WEBSITE

## A.1 Persiapan (sekali saja)

1. Pasang **Node.js versi LTS** dari nodejs.org (minimal versi 18). Cek di terminal: `node -v`.
2. Buka terminal di folder project `toko-jaringan` (folder yang berisi `package.json`).
3. Pasang semua library:
   ```bash
   npm install
   ```
4. Buat file `.env` dari contoh (file ini menyimpan rahasia dan **tidak boleh di-commit**):

   | Sistem | Perintah |
   |---|---|
   | macOS / Linux / Git Bash | `cp .env.example .env` |
   | Windows CMD | `copy .env.example .env` |
   | Windows PowerShell | `Copy-Item .env.example .env` |

5. Buka `.env` dengan editor teks lalu isi:
   ```
   PORT=3000
   SESSION_SECRET=isi-dengan-teks-acak-yang-panjang
   ADMIN_EMAIL=admin@toko.test
   ADMIN_PASSWORD=isi-password-admin-anda
   ```
6. Jalankan server:
   ```bash
   npm start
   ```
   Jika berhasil, terminal menampilkan: `Toko Jaringan berjalan di http://localhost:3000`
7. Buka browser dan masuk ke **http://localhost:3000**
8. Menghentikan server: tekan `Ctrl + C` di terminal.

Saat pertama kali dijalankan, aplikasi otomatis membuat file database `data.sqlite`, 5 kategori, 10 produk contoh, dan **satu akun admin** dari nilai `ADMIN_EMAIL` dan `ADMIN_PASSWORD`.

Tips saat mengembangkan: pakai `npm run dev` agar server restart sendiri setiap kode diubah.

## A.2 Menjalankan sebagai CUSTOMER (pembeli)

Customer tidak perlu menjalankan apa pun selain membuka website. Alurnya:

| Langkah | Yang dilakukan | Alamat |
|---|---|---|
| 1 | Buka katalog (boleh tanpa login). Semua produk tampil. | `http://localhost:3000/` |
| 2 | Cari produk di kotak pencarian atau pilih kategori, lalu klik **Cari**. Klik **Reset** untuk kembali. | `/` |
| 3 | Klik nama produk untuk melihat detail (spesifikasi, garansi, berat, stok). | `/produk/1` dst. |
| 4 | Klik **Daftar**, isi nama, email, password (minimal 8 karakter), konfirmasi password. | `/register` |
| 5 | Setelah berhasil, masuk lewat **Masuk** dengan email dan password tadi. | `/login` |
| 6 | Di halaman detail produk, isi jumlah lalu klik **Tambah ke keranjang**. Angka di menu **Keranjang (n)** bertambah. | `/produk/:id` |
| 7 | Buka **Keranjang** untuk melihat item, total harga, dan total berat. | `/keranjang` |
| 8 | Ubah jumlah lalu klik **Ubah**, atau klik **Hapus** untuk membuang item. | `/keranjang` |
| 9 | Klik **Keluar** untuk logout. | menu atas |

Aturan yang akan Anda temui:
- Tamu (belum login) bisa melihat katalog dan detail, tetapi tombol beli menjadi **Masuk untuk membeli**.
- Jumlah di keranjang otomatis dibatasi sesuai stok. Produk stok 0 (contoh: LAN Tester RJ45) tidak bisa dibeli.
- Checkout dan pembayaran belum ada (Sprint 2).

## A.3 Menjalankan sebagai ADMIN (penjual)

Admin memakai website yang sama. Bedanya hanya akun yang dipakai login.

1. Buka `http://localhost:3000/login`.
2. Login dengan **email dan password admin dari file `.env`** (`ADMIN_EMAIL` dan `ADMIN_PASSWORD`).
3. Setelah login, admin otomatis diarahkan ke **Kelola Produk** (`/admin/produk`).

| Tugas admin | Cara |
|---|---|
| Melihat semua produk | Menu **Kelola Produk** (`/admin/produk`) |
| Menambah produk | Klik **+ Tambah Produk**, isi form (nama, merek, kategori, harga, stok, **berat gram**, garansi, deskripsi, spesifikasi, dan **foto produk** dengan memilih file JPG/PNG/WEBP maksimal 2 MB), klik **Simpan** |
| Mengubah produk | Klik **Ubah** pada baris produk, edit, klik **Simpan** |
| Menghapus produk | Klik **Hapus**, konfirmasi pada kotak dialog |
| Mengganti foto | Klik **Ubah**, pilih file foto baru, **Simpan**. Foto lama otomatis terhapus. Tanpa memilih file, foto lama dipertahankan |
| Melihat hasilnya di toko | Klik menu **Katalog**; produk baru langsung tampil untuk customer |

Tentang foto produk: file yang diunggah disimpan otomatis di folder `public/assets/gambar/` dan alamatnya di database berbentuk `assets/gambar/nama-file.jpg`. Folder itu bagian dari project, jadi **commit file fotonya ke GitHub** agar anggota tim lain juga melihat gambarnya. Produk contoh awal belum punya foto; ubah tiap produk lalu unggah fotonya.

Hal penting tentang akun admin:
- Admin **tidak dibuat lewat halaman Daftar**. Halaman Daftar hanya membuat akun pembeli. Admin dibuat otomatis dari `.env`.
- Admin hanya dibuat **satu kali**, yaitu saat database pertama dibuat. Mengubah `ADMIN_PASSWORD` di `.env` setelah itu **tidak** mengubah password admin yang sudah ada. Untuk mengatur ulang: hentikan server, hapus file `data.sqlite`, `data.sqlite-shm`, dan `data.sqlite-wal`, lalu `npm start` lagi (semua data ikut kembali ke data contoh).
- Jika file `.env` tidak ada sama sekali, akun bawaan adalah `admin@toko.test` dengan password `admin12345` (hanya untuk uji lokal). Jika Anda menyalin `.env.example` tanpa mengubahnya, passwordnya adalah teks `ganti-password-admin`.
- Akun pembeli yang mencoba membuka `/admin/produk` mendapat halaman **Akses ditolak**. Admin tidak memakai keranjang.

## A.4 Demo customer dan admin sekaligus (untuk Monev)

Sesi login tersimpan di browser. Jika customer dan admin login di browser yang sama, akun yang terakhir login menggantikan yang sebelumnya. Untuk demo dua peran bersamaan:
- Browser biasa untuk **admin**, jendela **Incognito/Private** untuk **customer** (atau dua browser berbeda).
- Contoh demo: admin menambah produk baru di jendela pertama, lalu customer me-refresh katalog di jendela kedua dan produk itu muncul.

## A.5 Masalah umum

| Masalah | Penyebab dan solusi |
|---|---|
| `npm install` gagal di `better-sqlite3` | Pakai Node.js LTS (20 atau 22). Di Windows, pasang Node dari nodejs.org dan ulangi `npm install`. |
| `Error: listen EADDRINUSE :::3000` | Port 3000 sedang dipakai. Ubah `PORT=3001` di `.env`, lalu buka `http://localhost:3001`. |
| Perubahan kode tidak terlihat | Server belum restart. Tekan `Ctrl + C`, jalankan `npm start` lagi, atau pakai `npm run dev`. |
| Lupa password admin | Lihat A.3: hapus `data.sqlite*` lalu jalankan ulang. |
| Halaman fitur 404 | Folder fitur itu belum ada di `fitur/`. Saat `npm start`, terminal menampilkan daftar "Fitur dimuat"; cek apakah folder yang dimaksud ada di daftar. |
| `Failed to lookup view "..."` | Nama file `.ejs` tidak cocok dengan yang dipanggil `res.render()`, atau file tampilan tidak berada di folder fiturnya. |
| Foto ditolak saat disimpan | Format harus JPG, PNG, atau WEBP dan ukuran maksimal 2 MB. SVG sengaja tidak diizinkan. |
| `Cannot find module 'multer'` | Jalankan `npm install` (multer sudah tercantum di `package.json`). |
| Login admin gagal padahal sudah ubah `.env` | Admin sudah terlanjur dibuat dengan password lama. Lihat A.3. |


---

# BAGIAN B — CARA KERJA STRUKTUR `fitur/`

## B.1 Peta folder

```
toko-jaringan/
├── FONDASI (dimasukkan lebih dulu, kartu PPL-24)
│   ├── server.js            aplikasi utama
│   ├── muat-fitur.js        pemuat otomatis semua folder di fitur/
│   ├── db.js                skema database + data contoh
│   ├── middleware/auth.js   pengaman halaman (login, pembeli, admin)
│   ├── views/               header, footer, halaman error
│   ├── public/              style.css dan folder foto assets/gambar/
│   ├── package.json, .env.example, .gitignore
│   └── README.md, docs/
│
└── fitur/                   ← satu folder = satu bagian pekerjaan
    ├── 01-registrasi/       route + tampilan + README
    ├── 02-login/
    ├── 03-logout/
    ├── 04-crud-produk/
    ├── 05-katalog/
    ├── 06-cari-filter/
    ├── 07-detail-produk/
    └── 08-keranjang/
```

## B.2 Bagaimana folder-folder itu terhubung

1. `server.js` memanggil `muatFitur(app)` dari `muat-fitur.js`.
2. `muat-fitur.js` membaca isi folder `fitur/`, lalu memuat `middleware.js` (kalau ada) dan `index.js` dari tiap folder, berurutan menurut nama folder. Saat `npm start`, terminal mencetak daftar **Fitur dimuat** sehingga mudah dicek fitur mana yang aktif.
3. Tampilan (`.ejs`) tiap fitur berada di folder fitur itu sendiri. Nama file tampilan harus **unik** antar fitur (contoh: `katalog.ejs`, `keranjang.ejs`), dan `res.render('katalog', ...)` memakai nama tanpa `.ejs`.
4. Header dan footer bersama dipanggil dengan `<%- include('partials/header') %>` dari fondasi.
5. Fitur 5 dan 6 bekerja sama lewat dua nilai: fitur 6 mengisi `res.locals.filter` (potongan SQL) dan `res.locals.filterForm` (HTML form cari), lalu fitur 5 memakainya. Kalau fitur 6 belum ada, katalog tetap jalan dan menampilkan semua produk.

Akibatnya:
- Menambah fitur = menaruh foldernya. Tidak perlu mengedit `server.js`.
- Fitur boleh digabung dalam urutan apa pun tanpa membuat server rusak. Fitur yang belum ada hanya membuat halamannya 404.
- Setiap anggota hanya menyentuh foldernya sendiri, jadi Pull Request tidak saling bentrok.

## B.3 Pembagian 8 fitur

| No | Folder | Kartu | Ukuran | Pemegang | Bergantung pada |
|---|---|---|---|---|---|
| 1 | `01-registrasi` | PPL-01 | Sedang | (isi nama) | Fondasi |
| 2 | `02-login` | PPL-01 | Sedang | (isi nama) | Fondasi |
| 3 | `03-logout` | PPL-01 | Kecil | (isi nama) | Fondasi; dicoba setelah 2 |
| 4 | `04-crud-produk` | PPL-02 | Besar | (isi nama) | Fondasi; dicoba setelah 2 |
| 5 | `05-katalog` | PPL-03 | Kecil | (isi nama) | Fondasi |
| 6 | `06-cari-filter` | PPL-04 | Kecil | (isi nama) | Fitur 5 |
| 7 | `07-detail-produk` | PPL-05 | Kecil | (isi nama) | Fondasi |
| 8 | `08-keranjang` | PPL-06 | Besar | (isi nama) | Fondasi; dicoba setelah 1, 2, 7 |

Saran pembagian: berikan fitur **Besar** (4 dan 8) kepada anggota yang paling siap, dan pasangkan fitur **Kecil** dengan anggota yang baru belajar. QA menguji lewat tabel Uji di tiap fitur, UI/UX memeriksa tampilan `.ejs` dan `public/style.css` bersama pemegang fitur.

**Tentang kode kartu.** PPL-01 dipegang tiga orang (fitur 1, 2, 3). Pada board, beri kartu PPL-01 tiga penanggung jawab, dan bedakan PR dengan nama branch dan judulnya (`feature/PPL-01-registrasi`, `feature/PPL-01-login`, `feature/PPL-01-logout`). Jika board Anda hanya mengizinkan satu penanggung jawab per kartu, buat tiga kartu baru (misalnya PPL-31, PPL-32, PPL-33) dan ganti kodenya secara konsisten di kartu, branch, commit, dan judul PR.

## B.4 Urutan merge yang disarankan

Urutan ini hanya agar fitur bisa dicoba utuh. Server tetap jalan bila urutannya berbeda.

| Urutan | Fitur | Alasan |
|---|---|---|
| 0 | Fondasi (PPL-24) | Semua fitur butuh fondasi |
| 1 | 02 Login | Admin bisa dicoba tanpa fitur lain |
| 2 | 04 CRUD produk | Admin mengisi produk dan foto |
| 3 | 05 Katalog | Menampilkan produk yang diisi admin |
| 4 | 06 Cari dan filter | Memperluas katalog |
| 5 | 07 Detail produk | Halaman tujuan dari katalog |
| 6 | 01 Registrasi | Pembeli bisa membuat akun |
| 7 | 03 Logout | Melengkapi alur akun |
| 8 | 08 Keranjang | Butuh pembeli, login, dan detail produk |

---

# BAGIAN C — TAHAP 0: FONDASI (kartu PPL-24)

Dikerjakan **sekali** oleh pemegang project (atau Backend) sebelum anggota lain mulai. Isinya semua file di luar `fitur/`.

```bash
git checkout main && git pull
git checkout -b feature/PPL-24-fondasi
git add . ":(exclude)fitur"
git commit -m "[PPL-24] siapkan fondasi project"
git push -u origin feature/PPL-24-fondasi
# buka Pull Request berjudul: [PPL-24] Fondasi project, review, merge ke main
```

Catatan: `":(exclude)fitur"` membuat folder `fitur/` tidak ikut ter-commit (butuh Git 2.13 ke atas). Tanda kutip ganda berlaku di Windows dan Mac/Linux. `.env` dan `data.sqlite` otomatis diabaikan oleh `.gitignore`.

Setelah fondasi di-merge ke `main`, semua anggota menjalankan `git pull` dan `npm install`.

Kode fondasi:


### C.1 `package.json`

```json
{
  "name": "toko-jaringan",
  "version": "0.1.0",
  "description": "E-commerce UMKM toko peralatan jaringan (PPL Sprint 1)",
  "main": "server.js",
  "scripts": {
    "start": "node server.js",
    "dev": "node --watch server.js"
  },
  "dependencies": {
    "bcryptjs": "^2.4.3",
    "better-sqlite3": "^11.5.0",
    "dotenv": "^16.4.5",
    "ejs": "^3.1.10",
    "express": "^4.21.0",
    "express-session": "^1.18.0",
    "multer": "^2.4.0"
  }
}
```

### C.2 `.env.example`

```bash
# Salin file ini menjadi .env lalu isi nilainya. JANGAN commit file .env
PORT=3000
SESSION_SECRET=ganti-dengan-string-acak-panjang

# Akun admin awal (dibuat otomatis saat pertama kali dijalankan)
ADMIN_EMAIL=admin@toko.test
ADMIN_PASSWORD=ganti-password-admin

# Sprint 2 dan seterusnya (jangan diisi di repo):
# MIDTRANS_SERVER_KEY=
# MIDTRANS_CLIENT_KEY=
# ONGKIR_API_KEY=
```

### C.3 `.gitignore`

```bash
node_modules/
.env
*.sqlite
*.sqlite-shm
*.sqlite-wal
.DS_Store
```

### C.4 `db.js`

```js
// Koneksi database SQLite + skema + data awal (seed)
// Terkait: PPL-01 (users), PPL-02 (products, categories), PPL-06 (cart_items)
const Database = require('better-sqlite3');
const bcrypt = require('bcryptjs');
const path = require('path');

const db = new Database(process.env.DB_FILE || path.join(__dirname, 'data.sqlite'));
db.pragma('journal_mode = WAL');
db.pragma('foreign_keys = ON');

db.exec(`
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'buyer' CHECK (role IN ('buyer','admin')),
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  category_id INTEGER NOT NULL REFERENCES categories(id),
  name TEXT NOT NULL,
  brand TEXT,
  description TEXT,
  specs TEXT,
  warranty TEXT,
  price INTEGER NOT NULL CHECK (price >= 0),
  stock INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
  weight_gram INTEGER NOT NULL CHECK (weight_gram > 0),
  image_url TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  qty INTEGER NOT NULL CHECK (qty > 0),
  UNIQUE (user_id, product_id)
);
`);

function seed() {
  const catCount = db.prepare('SELECT COUNT(*) c FROM categories').get().c;
  if (catCount === 0) {
    const ins = db.prepare('INSERT INTO categories (name) VALUES (?)');
    ['Router', 'Switch', 'Access Point', 'Kabel', 'Aksesoris'].forEach((n) => ins.run(n));
  }

  const adminEmail = process.env.ADMIN_EMAIL || 'admin@toko.test';
  const adminPass = process.env.ADMIN_PASSWORD || 'admin12345';
  const hasAdmin = db.prepare("SELECT id FROM users WHERE role='admin' LIMIT 1").get();
  if (!hasAdmin) {
    db.prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)')
      .run('Admin Toko', adminEmail, bcrypt.hashSync(adminPass, 10), 'admin');
  }

  const prodCount = db.prepare('SELECT COUNT(*) c FROM products').get().c;
  if (prodCount === 0) {
    const cat = (n) => db.prepare('SELECT id FROM categories WHERE name=?').get(n).id;
    const ins = db.prepare(`INSERT INTO products
      (category_id,name,brand,description,specs,warranty,price,stock,weight_gram,image_url)
      VALUES (?,?,?,?,?,?,?,?,?,?)`);
    const rows = [
      [cat('Router'), 'Router WiFi AC1200 Dual Band', 'TP-Link', 'Router dual band untuk rumah dan kantor kecil.', '867 Mbps (5GHz) + 300 Mbps (2.4GHz)\n4 antena eksternal\n4 port LAN 100 Mbps', '1 tahun', 350000, 25, 450, ''],
      [cat('Router'), 'Router Gigabit 5 Port', 'Mikrotik', 'Router fleksibel dengan RouterOS.', '5 port Gigabit Ethernet\nCPU 650 MHz\nRouterOS L4', '1 tahun', 780000, 12, 300, ''],
      [cat('Switch'), 'Switch Gigabit 8 Port', 'TP-Link', 'Switch unmanaged plug and play.', '8 port 10/100/1000 Mbps\nCasing metal\nTanpa konfigurasi', '2 tahun', 280000, 30, 500, ''],
      [cat('Switch'), 'Switch PoE 8 Port', 'Ruijie', 'Switch dengan PoE untuk CCTV dan AP.', '8 port PoE 100 Mbps\nTotal PoE 96 W', '2 tahun', 620000, 4, 700, ''],
      [cat('Access Point'), 'Access Point Ceiling AC1200', 'Ubiquiti', 'AP langit-langit untuk area luas.', 'Dual band AC1200\nPoE 802.3af\nHingga 100 klien', '1 tahun', 1150000, 8, 350, ''],
      [cat('Kabel'), 'Kabel UTP Cat6 per Roll 305m', 'Belden', 'Kabel UTP Cat6 untuk instalasi LAN.', 'Cat6 solid copper\nPanjang 305 meter\nAWG 23', '-', 1450000, 10, 9500, ''],
      [cat('Kabel'), 'Patch Cord Cat6 2 Meter', 'Ugreen', 'Kabel patch siap pakai.', 'Cat6 UTP\nPanjang 2 meter\nKonektor RJ45', '-', 25000, 100, 80, ''],
      [cat('Aksesoris'), 'Tang Crimping RJ45/RJ11', 'Krisbow', 'Tang crimping untuk konektor RJ45 dan RJ11.', 'Baja karbon\nDilengkapi pemotong kabel', '6 bulan', 95000, 3, 400, ''],
      [cat('Aksesoris'), 'Konektor RJ45 Cat6 isi 100', 'Amp', 'Konektor RJ45 Cat6 per pak.', 'Cat6\nIsi 100 pcs\nKontak berlapis emas', '-', 120000, 40, 250, ''],
      [cat('Aksesoris'), 'LAN Tester RJ45', 'Generic', 'Penguji kabel LAN.', 'Uji RJ45/RJ11\nBaterai 9V', '3 bulan', 65000, 0, 200, ''],
    ];
    const tx = db.transaction(() => rows.forEach((r) => ins.run(...r)));
    tx();
  }
}
seed();

module.exports = db;
```

### C.5 `server.js`

```js
// Server utama — toko jaringan (Sprint 1)
require('dotenv').config();
const express = require('express');
const session = require('express-session');
const path = require('path');
const db = require('./db');
const { muatFitur, viewDirs } = require('./muat-fitur');

const app = express();
app.set('view engine', 'ejs');
app.set('views', viewDirs()); // views/ (fondasi) + folder tampilan tiap fitur di fitur/

app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, 'public')));
app.use(
  session({
    secret: process.env.SESSION_SECRET || 'dev-secret-ganti-di-env',
    resave: false,
    saveUninitialized: false,
    cookie: { httpOnly: true, sameSite: 'lax', maxAge: 1000 * 60 * 60 * 8 },
  })
);

// Data yang tersedia di semua view
app.use((req, res, next) => {
  const u = req.session.user || null;
  res.locals.user = u;
  res.locals.flash = req.session.flash || null;
  delete req.session.flash;
  res.locals.rupiah = (n) => 'Rp' + Number(n).toLocaleString('id-ID');
  // 'assets/gambar/rj45.jpg' -> '/assets/gambar/rj45.jpg' (URL http(s) lama tetap dipakai apa adanya)
  res.locals.imgSrc = (u) => (!u ? '' : /^https?:\/\//i.test(u) ? u : '/' + u.replace(/^\/+/, ''));
  res.locals.filterForm = ''; // diisi oleh fitur 6 (cari-filter) bila fitur itu ada
  res.locals.cartCount = 0;
  if (u && u.role === 'buyer') {
    res.locals.cartCount = db
      .prepare('SELECT COALESCE(SUM(qty),0) c FROM cart_items WHERE user_id=?')
      .get(u.id).c;
  }
  next();
});

// Memuat semua folder di fitur/ secara otomatis (lihat muat-fitur.js)
muatFitur(app);

app.use((req, res) => {
  res.status(404).render('error', { title: 'Tidak ditemukan', message: 'Halaman tidak ditemukan.' });
});

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).render('error', { title: 'Kesalahan', message: 'Terjadi kesalahan pada server.' });
});

const PORT = process.env.PORT || 3000;
if (require.main === module) {
  app.listen(PORT, () => console.log(`Toko Jaringan berjalan di http://localhost:${PORT}`));
}
module.exports = app;
```

### C.6 `muat-fitur.js`

```js
// Pemuat fitur otomatis.
//
// Setiap folder di fitur/ adalah SATU fitur (satu kartu). Server memuat semuanya
// secara otomatis, jadi menambah fitur = cukup menaruh foldernya. Tidak perlu
// mengubah server.js, sehingga anggota tim tidak saling bentrok di GitHub.
//
// Isi folder sebuah fitur:
//   index.js       route fitur (wajib kecuali fitur hanya berupa middleware)
//   middleware.js  opsional. Dimuat LEBIH DULU dari semua index.js, dipakai fitur
//                  yang "menyisipkan" sesuatu ke fitur lain (contoh: cari-filter)
//   *.ejs          tampilan milik fitur itu (nama file harus unik antar fitur)
//
// Urutan muat = urutan nama folder (01-, 02-, ...).
const fs = require('fs');
const path = require('path');

const FITUR_DIR = path.join(__dirname, 'fitur');

function namaFitur() {
  if (!fs.existsSync(FITUR_DIR)) return [];
  return fs
    .readdirSync(FITUR_DIR, { withFileTypes: true })
    .filter((d) => d.isDirectory())
    .map((d) => d.name)
    .sort();
}

// Folder tampilan: views/ (fondasi) + folder setiap fitur
function viewDirs() {
  return [path.join(__dirname, 'views'), ...namaFitur().map((n) => path.join(FITUR_DIR, n))];
}

function muatFitur(app) {
  const names = namaFitur();
  const dimuat = [];

  for (const n of names) {
    const f = path.join(FITUR_DIR, n, 'middleware.js');
    if (fs.existsSync(f)) {
      app.use(require(f));
      dimuat.push(`${n}/middleware.js`);
    }
  }
  for (const n of names) {
    const f = path.join(FITUR_DIR, n, 'index.js');
    if (fs.existsSync(f)) {
      app.use(require(f));
      dimuat.push(`${n}/index.js`);
    }
  }
  console.log(dimuat.length ? 'Fitur dimuat:\n  ' + dimuat.join('\n  ') : 'Belum ada fitur di folder fitur/');
}

module.exports = { muatFitur, viewDirs };
```

### C.7 `middleware/auth.js`

```js
// Middleware otorisasi — PPL-01 (login) & PPL-02 (hanya admin)
function requireLogin(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login terlebih dahulu.' };
    return res.redirect('/login');
  }
  next();
}

function requireBuyer(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login untuk memakai keranjang.' };
    return res.redirect('/login');
  }
  if (req.session.user.role !== 'buyer') {
    req.session.flash = { type: 'error', text: 'Keranjang hanya untuk akun pembeli.' };
    return res.redirect('/');
  }
  next();
}

function requireAdmin(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login terlebih dahulu.' };
    return res.redirect('/login');
  }
  if (req.session.user.role !== 'admin') {
    return res.status(403).render('error', { title: 'Akses ditolak', message: 'Halaman ini hanya untuk admin.' });
  }
  next();
}

module.exports = { requireLogin, requireBuyer, requireAdmin };
```

### C.8 `views/partials/header.ejs`

```html
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><%= title %> — Toko Jaringan</title>
  <link rel="stylesheet" href="/style.css">
</head>
<body>
<header class="topbar">
  <div class="wrap bar">
    <a class="brand" href="/">Toko Jaringan</a>
    <nav>
      <a href="/">Katalog</a>
      <% if (user && user.role === 'admin') { %>
        <a href="/admin/produk">Kelola Produk</a>
      <% } %>
      <% if (user && user.role === 'buyer') { %>
        <a href="/keranjang">Keranjang (<%= cartCount %>)</a>
      <% } %>
      <% if (user) { %>
        <span class="who"><%= user.name %></span>
        <form method="post" action="/logout" class="inline"><button class="link">Keluar</button></form>
      <% } else { %>
        <a href="/login">Masuk</a>
        <a href="/register">Daftar</a>
      <% } %>
    </nav>
  </div>
</header>
<main class="wrap">
<% if (flash) { %>
  <div class="flash <%= flash.type %>"><%= flash.text %></div>
<% } %>
```

### C.9 `views/partials/footer.ejs`

```html
</main>
<footer class="foot">
  <div class="wrap">Toko Jaringan — proyek PPL. Produk perangkat jaringan: router, switch, access point, kabel, aksesoris.</div>
</footer>
</body>
</html>
```

### C.10 `views/error.ejs`

```html
<%- include('partials/header') %>
<h1><%= title %></h1>
<p><%= message %></p>
<p><a href="/">Kembali ke katalog</a></p>
<%- include('partials/footer') %>
```

### C.11 `public/style.css`

```css
:root { --bg:#f6f7f9; --fg:#1d2330; --muted:#6b7280; --accent:#1f5eff; --line:#e3e6ec; --danger:#c62828; --ok:#1b7f3b; }
* { box-sizing: border-box; }
body { margin:0; font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background:var(--bg); color:var(--fg); line-height:1.5; }
.wrap { max-width: 1040px; margin: 0 auto; padding: 0 16px; }
.topbar { background:#fff; border-bottom:1px solid var(--line); position:sticky; top:0; z-index:5; }
.bar { display:flex; align-items:center; justify-content:space-between; height:56px; gap:12px; }
.brand { font-weight:700; font-size:1.15rem; color:var(--accent); text-decoration:none; }
nav { display:flex; align-items:center; gap:14px; flex-wrap:wrap; }
nav a { color:var(--fg); text-decoration:none; }
nav a:hover { color:var(--accent); }
.who { color:var(--muted); font-size:.9rem; }
main { padding-top:20px; padding-bottom:40px; min-height:70vh; }
h1 { font-size:1.5rem; margin:.2em 0 .6em; }
a { color:var(--accent); }
.muted { color:var(--muted); }
.right { text-align:right; }
.inline { display:inline; margin:0; }
button.link { background:none; border:0; color:var(--accent); cursor:pointer; font:inherit; padding:0; }
.btn { display:inline-block; background:var(--accent); color:#fff; border:0; border-radius:6px; padding:8px 14px; cursor:pointer; text-decoration:none; font:inherit; }
.btn:hover { filter:brightness(.92); }
.btn.ghost { background:#fff; color:var(--fg); border:1px solid var(--line); }
.btn.small { padding:4px 10px; font-size:.85rem; }
.btn.danger { background:var(--danger); }
.flash { padding:10px 14px; border-radius:6px; margin-bottom:16px; border:1px solid var(--line); background:#fff; }
.flash.success { border-color:#b9e2c5; background:#eefaf1; color:var(--ok); }
.flash.error { border-color:#f1c0c0; background:#fdf0f0; color:var(--danger); }
.flash ul { margin:0; padding-left:18px; }
.filters { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:20px; }
input[type=text], input[type=email], input[type=password], input[type=number], select, textarea {
  padding:8px 10px; border:1px solid var(--line); border-radius:6px; font:inherit; background:#fff; width:100%;
}
.filters input[type=text] { width:260px; }
.filters select { width:200px; }
.grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:16px; }
.card { background:#fff; border:1px solid var(--line); border-radius:10px; overflow:hidden; display:flex; flex-direction:column; }
.thumb, .pic { display:flex; align-items:center; justify-content:center; background:#eceff4; color:var(--muted); aspect-ratio:4/3; text-decoration:none; }
.thumb img, .pic img { width:100%; height:100%; object-fit:cover; }
.card .body { padding:12px; display:flex; flex-direction:column; gap:4px; }
.card h3 { font-size:1rem; margin:0; }
.card h3 a { color:var(--fg); text-decoration:none; }
.price { font-weight:700; color:var(--accent); }
.price.big { font-size:1.6rem; margin:6px 0; }
.badge.out { display:inline-block; background:#fdecea; color:var(--danger); border-radius:4px; padding:2px 8px; font-size:.8rem; width:fit-content; }
.empty { padding:30px; text-align:center; background:#fff; border:1px dashed var(--line); border-radius:8px; }
.detail { display:grid; grid-template-columns:minmax(0,1fr) minmax(0,1.2fr); gap:28px; background:#fff; border:1px solid var(--line); border-radius:10px; padding:20px; }
.detail .pic { border-radius:8px; overflow:hidden; }
.row { display:flex; gap:8px; align-items:center; flex-wrap:wrap; }
.row.between { justify-content:space-between; }
.row input[type=number] { width:90px; }
.form { display:flex; flex-direction:column; gap:12px; background:#fff; border:1px solid var(--line); border-radius:10px; padding:20px; }
.form.narrow { max-width:420px; }
.form label { display:flex; flex-direction:column; gap:4px; font-size:.9rem; }
.cols { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; }
.table { width:100%; border-collapse:collapse; background:#fff; border:1px solid var(--line); border-radius:8px; overflow:hidden; }
.table th, .table td { padding:10px 12px; border-bottom:1px solid var(--line); text-align:left; vertical-align:middle; }
.table thead { background:#f0f2f6; }
.foot { border-top:1px solid var(--line); background:#fff; padding:16px 0; color:var(--muted); font-size:.85rem; }
@media (max-width:700px) { .detail { grid-template-columns:1fr; } .cols { grid-template-columns:1fr; } .table { font-size:.85rem; } }
.preview { display:block; max-width:200px; border:1px solid var(--line); border-radius:8px; margin-bottom:6px; }
```

---

# BAGIAN D — KODE PER FITUR

Setiap fitur di bawah ini berisi file-filenya lengkap dengan kode, tabel uji, dan perintah git. Isi yang sama juga ada di `fitur/<folder>/README.md` pada project.


## Fitur 1 — Registrasi

**Kartu:** PPL-01 · **Ukuran:** Sedang · **Pemegang:** (isi nama)

Calon pembeli membuat akun: nama, email, password (minimal 8 karakter), dan konfirmasi password. Password disimpan sebagai hash (bcrypt). Akun yang dibuat dari halaman ini selalu berperan pembeli.

### Isi folder `fitur/01-registrasi/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET dan POST `/register`, validasi input, simpan akun |
| `registrasi.ejs` | Tampilan form pendaftaran |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /register`
- `POST /register`

### Bergantung pada

Fondasi saja. Setelah berhasil daftar, pengguna diarahkan ke `/login` (fitur 2). Bila fitur 2 belum digabung, halaman itu masih 404 dan itu wajar.

### Cara mencoba

- Customer: buka `http://localhost:3000/register`, isi form, klik **Daftar**.
- Admin: tidak lewat halaman ini. Akun admin dibuat otomatis dari `.env`.

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Isi email `bukan-email` | Ditolak: "Format email tidak valid." |
| 2 | Isi password hanya 3 karakter | Ditolak: "Password minimal 8 karakter." |
| 3 | Isi konfirmasi password yang berbeda | Ditolak: "Konfirmasi password tidak sama." |
| 4 | Isi semua data dengan benar | Diarahkan ke `/login` dengan pesan sukses |
| 5 | Daftar lagi dengan email yang sama | Ditolak: "Email sudah terdaftar." |
| 6 | Buka `data.sqlite` dengan DB Browser for SQLite, tabel `users` | `password_hash` berisi teks acak berawalan `$2`, bukan password asli; `role` = buyer |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-01-registrasi
# taruh folder fitur/01-registrasi/ di project, lalu uji: npm start
git add fitur/01-registrasi
git commit -m "[PPL-01] tambah registrasi pembeli"
git push -u origin feature/PPL-01-registrasi
# buka Pull Request berjudul: [PPL-01] Registrasi pembeli
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-01 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `01-registrasi`
- [ ] Tidak ada file di luar `fitur/01-registrasi/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/01-registrasi/index.js`

```js
// FITUR 1 — REGISTRASI PEMBELI (kartu PPL-01)
// Alamat   : GET /register (form), POST /register (simpan akun pembeli baru)
// Tampilan : registrasi.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel users)
const express = require('express');
const bcrypt = require('bcryptjs');
const db = require('../../db');

const router = express.Router();
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

router.get('/register', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('registrasi', { title: 'Daftar', form: {}, errors: [] });
});

router.post('/register', (req, res) => {
  const name = (req.body.name || '').trim();
  const email = (req.body.email || '').trim().toLowerCase();
  const password = req.body.password || '';
  const confirm = req.body.confirm || '';
  const errors = [];

  if (name.length < 2) errors.push('Nama minimal 2 karakter.');
  if (!EMAIL_RE.test(email)) errors.push('Format email tidak valid.');
  if (password.length < 8) errors.push('Password minimal 8 karakter.');
  if (password !== confirm) errors.push('Konfirmasi password tidak sama.');
  if (!errors.length && db.prepare('SELECT id FROM users WHERE email=?').get(email)) {
    errors.push('Email sudah terdaftar.');
  }
  if (errors.length) {
    return res.status(400).render('registrasi', { title: 'Daftar', form: { name, email }, errors });
  }

  // Password disimpan sebagai hash, bukan teks asli. Akun baru selalu berperan 'buyer'.
  db.prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)').run(
    name,
    email,
    bcrypt.hashSync(password, 10),
    'buyer'
  );
  req.session.flash = { type: 'success', text: 'Registrasi berhasil. Silakan login.' };
  res.redirect('/login');
});

module.exports = router;
```

### `fitur/01-registrasi/registrasi.ejs`

```html
<%- include('partials/header') %>
<h1>Daftar Akun</h1>
<% if (errors.length) { %>
  <div class="flash error"><ul><% errors.forEach(e => { %><li><%= e %></li><% }) %></ul></div>
<% } %>
<form method="post" action="/register" class="form narrow">
  <label>Nama <input type="text" name="name" value="<%= form.name || '' %>" required></label>
  <label>Email <input type="email" name="email" value="<%= form.email || '' %>" required></label>
  <label>Password (min. 8 karakter) <input type="password" name="password" required minlength="8"></label>
  <label>Konfirmasi password <input type="password" name="confirm" required minlength="8"></label>
  <button class="btn">Daftar</button>
  <p>Sudah punya akun? <a href="/login">Masuk</a></p>
</form>
<%- include('partials/footer') %>
```

---

## Fitur 2 — Login

**Kartu:** PPL-01 · **Ukuran:** Sedang · **Pemegang:** (isi nama)

Pembeli dan admin masuk dengan email dan password. Admin diarahkan ke Kelola Produk, pembeli ke beranda. Sesi dibuat ulang saat login.

### Isi folder `fitur/02-login/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET dan POST `/login`, cek password terhadap hash |
| `login.ejs` | Tampilan form login |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /login`
- `POST /login`

### Bergantung pada

Fondasi saja. Login admin bisa dicoba tanpa fitur 1 (akun admin dari `.env`). Login pembeli butuh akun dari fitur 1.

### Cara mencoba

- Customer: buka `http://localhost:3000/login`, masuk dengan akun yang didaftarkan.
- Admin: masuk dengan `ADMIN_EMAIL` dan `ADMIN_PASSWORD` dari `.env`; diarahkan ke `/admin/produk` (404 bila fitur 4 belum digabung, wajar).

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Login admin dengan data dari `.env` | Masuk, diarahkan ke `/admin/produk` |
| 2 | Login dengan password salah | Ditolak: "Email atau password salah." |
| 3 | Login dengan akun pembeli (dari fitur 1) | Masuk dan diarahkan ke beranda |
| 4 | Setelah login, buka `/login` lagi | Diarahkan ke beranda |
| 5 | Lihat menu atas | Menampilkan nama akun; menu Masuk dan Daftar hilang |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-01-login
# taruh folder fitur/02-login/ di project, lalu uji: npm start
git add fitur/02-login
git commit -m "[PPL-01] tambah login"
git push -u origin feature/PPL-01-login
# buka Pull Request berjudul: [PPL-01] Login pembeli dan admin
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-01 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `02-login`
- [ ] Tidak ada file di luar `fitur/02-login/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/02-login/index.js`

```js
// FITUR 2 — LOGIN (kartu PPL-01)
// Alamat   : GET /login (form), POST /login (cek email + password)
// Tampilan : login.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel users)
// Catatan  : akun admin dibuat otomatis dari .env oleh db.js, jadi login admin
//            bisa dicoba tanpa menunggu fitur Registrasi.
const express = require('express');
const bcrypt = require('bcryptjs');
const db = require('../../db');

const router = express.Router();

router.get('/login', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('login', { title: 'Login', form: {}, error: null });
});

router.post('/login', (req, res) => {
  const email = (req.body.email || '').trim().toLowerCase();
  const password = req.body.password || '';
  const row = db.prepare('SELECT * FROM users WHERE email=?').get(email);

  if (!row || !bcrypt.compareSync(password, row.password_hash)) {
    return res.status(401).render('login', { title: 'Login', form: { email }, error: 'Email atau password salah.' });
  }
  const sessionUser = { id: row.id, name: row.name, email: row.email, role: row.role };
  req.session.regenerate((err) => {
    if (err) throw err;
    req.session.user = sessionUser;
    // admin langsung ke halaman kelola produk, pembeli ke beranda
    res.redirect(row.role === 'admin' ? '/admin/produk' : '/');
  });
});

module.exports = router;
```

### `fitur/02-login/login.ejs`

```html
<%- include('partials/header') %>
<h1>Masuk</h1>
<% if (error) { %><div class="flash error"><%= error %></div><% } %>
<form method="post" action="/login" class="form narrow">
  <label>Email <input type="email" name="email" value="<%= form.email || '' %>" required></label>
  <label>Password <input type="password" name="password" required></label>
  <button class="btn">Masuk</button>
  <p>Belum punya akun? <a href="/register">Daftar</a></p>
</form>
<%- include('partials/footer') %>
```

---

## Fitur 3 — Logout

**Kartu:** PPL-01 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Menghapus sesi login lalu kembali ke beranda. Tombol **Keluar** sudah ada di menu atas (fondasi), fitur ini yang membuatnya berfungsi.

### Isi folder `fitur/03-logout/`

| File | Fungsi |
|---|---|
| `index.js` | Route POST `/logout` |
| `README.md` | Catatan ini |

### Alamat (route)

- `POST /logout`

### Bergantung pada

Fondasi saja, tetapi baru bisa dicoba setelah fitur 2 (login) ada.

### Cara mencoba

- Customer atau admin: setelah login, klik **Keluar** di menu atas.

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Login, lalu klik **Keluar** | Kembali ke beranda; menu Masuk dan Daftar muncul lagi |
| 2 | Setelah keluar, buka `/admin/produk` | Diarahkan ke `/login` (bila fitur 4 ada) |
| 3 | Setelah keluar, refresh halaman | Tetap terlihat belum login |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-01-logout
# taruh folder fitur/03-logout/ di project, lalu uji: npm start
git add fitur/03-logout
git commit -m "[PPL-01] tambah logout"
git push -u origin feature/PPL-01-logout
# buka Pull Request berjudul: [PPL-01] Logout
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-01 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `03-logout`
- [ ] Tidak ada file di luar `fitur/03-logout/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/03-logout/index.js`

```js
// FITUR 3 — LOGOUT (kartu PPL-01)
// Alamat   : POST /logout (tombol "Keluar" ada di menu atas, views/partials/header.ejs)
// Tampilan : tidak ada (langsung kembali ke beranda)
// Memakai  : fondasi saja (session dari server.js)
const express = require('express');

const router = express.Router();

router.post('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/'));
});

module.exports = router;
```

---

## Fitur 4 — CRUD Produk Admin

**Kartu:** PPL-02 · **Ukuran:** Besar · **Pemegang:** (isi nama)

Admin melihat, menambah, mengubah, dan menghapus produk (nama, merek, kategori, harga, stok, **berat gram**, garansi, deskripsi, spesifikasi) serta mengunggah foto. Foto disimpan di `public/assets/gambar/` dan alamatnya di database berbentuk `assets/gambar/rj45.jpg`. Semua alamat `/admin/...` hanya untuk admin.

### Isi folder `fitur/04-crud-produk/`

| File | Fungsi |
|---|---|
| `index.js` | Route CRUD, validasi form, upload foto (library multer), hapus file foto lama |
| `admin-produk-list.ejs` | Tampilan tabel daftar produk |
| `admin-produk-form.ejs` | Tampilan form tambah dan ubah produk (ada input foto) |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /admin/produk`
- `GET /admin/produk/baru`
- `POST /admin/produk`
- `GET /admin/produk/:id/edit`
- `POST /admin/produk/:id`
- `POST /admin/produk/:id/hapus`

### Bergantung pada

Fondasi (termasuk folder `public/assets/gambar/`). Untuk masuk sebagai admin dibutuhkan fitur 2 (login). Hasilnya terlihat di fitur 5 (katalog).

### Cara mencoba

- Admin: login, buka menu **Kelola Produk**, klik **+ Tambah Produk**, isi form, pilih foto (JPG, PNG, atau WEBP maksimal 2 MB), **Simpan**.
- Customer: tidak punya akses. Membuka `/admin/produk` mendapat "Akses ditolak".

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/admin/produk` tanpa login | Diarahkan ke `/login` |
| 2 | Login sebagai pembeli, buka `/admin/produk` | "Akses ditolak" (403) |
| 3 | Login admin, tambah produk dengan berat 0 | Ditolak: "Berat (gram) harus lebih dari 0." |
| 4 | Tambah produk data benar dengan foto `rj45.jpg` | Muncul di daftar; file ada di `public/assets/gambar/rj45.jpg` |
| 5 | Ubah harga tanpa memilih foto baru | Harga tersimpan; foto lama tetap |
| 6 | Ubah dengan memilih foto baru | Foto baru tampil; file foto lama terhapus dari folder |
| 7 | Unggah file `.txt` atau foto di atas 2 MB | Ditolak dengan pesan; produk tidak tersimpan |
| 8 | Hapus produk | Hilang dari daftar dan file fotonya ikut terhapus |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-02-crud-produk
# taruh folder fitur/04-crud-produk/ di project, lalu uji: npm start
git add fitur/04-crud-produk
git commit -m "[PPL-02] tambah CRUD produk admin dengan upload foto"
git push -u origin feature/PPL-02-crud-produk
# buka Pull Request berjudul: [PPL-02] Kelola produk admin
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-02 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `04-crud-produk`
- [ ] Tidak ada file di luar `fitur/04-crud-produk/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-02` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/04-crud-produk/index.js`

```js
// FITUR 4 — CRUD PRODUK ADMIN (kartu PPL-02)
// Alamat   : /admin/produk (daftar), /admin/produk/baru, /admin/produk/:id/edit,
//            POST /admin/produk, POST /admin/produk/:id, POST /admin/produk/:id/hapus
// Tampilan : admin-produk-list.ejs dan admin-produk-form.ejs (di folder ini)
// Foto     : diunggah lewat form, disimpan di public/assets/gambar/, alamatnya di database
//            berbentuk 'assets/gambar/rj45.jpg'
// Memakai  : fondasi (db.js, middleware/auth.js). Semua alamat /admin/... khusus admin.
const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const db = require('../../db');
const { requireAdmin } = require('../../middleware/auth');

const router = express.Router();
router.use('/admin', requireAdmin); // semua alamat /admin/... hanya untuk admin

// ---- Upload foto produk: disimpan ke public/assets/gambar/ ----
const UPLOAD_DIR = path.join(__dirname, '..', '..', 'public', 'assets', 'gambar');
fs.mkdirSync(UPLOAD_DIR, { recursive: true });

const ALLOWED = { 'image/jpeg': '.jpg', 'image/png': '.png', 'image/webp': '.webp' };

const upload = multer({
  storage: multer.diskStorage({
    destination: (req, file, cb) => cb(null, UPLOAD_DIR),
    filename: (req, file, cb) => {
      const ext = ALLOWED[file.mimetype];
      const base =
        path.parse(file.originalname).name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '') || 'produk';
      let name = base + ext; // contoh: rj45.jpg
      if (fs.existsSync(path.join(UPLOAD_DIR, name))) name = `${base}-${Date.now()}${ext}`; // jangan menimpa
      cb(null, name);
    },
  }),
  limits: { fileSize: 2 * 1024 * 1024 }, // maks. 2 MB
  fileFilter: (req, file, cb) => (ALLOWED[file.mimetype] ? cb(null, true) : cb(new Error('FORMAT'))),
});

// Middleware: baca field "foto"; kesalahan upload dicatat di req.uploadError
function uploadFoto(req, res, next) {
  upload.single('foto')(req, res, (err) => {
    if (err) {
      req.uploadError =
        err.code === 'LIMIT_FILE_SIZE' ? 'Ukuran foto maksimal 2 MB.'
        : err.message === 'FORMAT' ? 'Format foto harus JPG, PNG, atau WEBP.'
        : 'Upload foto gagal.';
    }
    next();
  });
}

const removeUploaded = (file) => file && fs.unlink(file.path, () => {}); // batalkan file yang terlanjur tersimpan
function removeImage(imageUrl) {
  if (imageUrl && imageUrl.startsWith('assets/gambar/')) {
    fs.unlink(path.join(UPLOAD_DIR, path.basename(imageUrl)), () => {}); // hapus file lama
  }
}

function parseForm(body) {
  const f = {
    name: (body.name || '').trim(),
    brand: (body.brand || '').trim(),
    category_id: parseInt(body.category_id, 10),
    description: (body.description || '').trim(),
    specs: (body.specs || '').trim(),
    warranty: (body.warranty || '').trim(),
    price: parseInt(body.price, 10),
    stock: parseInt(body.stock, 10),
    weight_gram: parseInt(body.weight_gram, 10),
  };
  const errors = [];
  if (f.name.length < 2) errors.push('Nama produk minimal 2 karakter.');
  if (!db.prepare('SELECT id FROM categories WHERE id=?').get(f.category_id)) errors.push('Kategori tidak valid.');
  if (!Number.isInteger(f.price) || f.price < 0) errors.push('Harga harus angka 0 atau lebih.');
  if (!Number.isInteger(f.stock) || f.stock < 0) errors.push('Stok harus angka 0 atau lebih.');
  if (!Number.isInteger(f.weight_gram) || f.weight_gram <= 0) errors.push('Berat (gram) harus lebih dari 0.');
  return { f, errors };
}

const categories = () => db.prepare('SELECT * FROM categories ORDER BY name').all();

router.get('/admin/produk', (req, res) => {
  const products = db
    .prepare(
      `SELECT p.*, c.name AS category_name FROM products p
       JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC`
    )
    .all();
  res.render('admin-produk-list', { title: 'Kelola Produk', products });
});

router.get('/admin/produk/baru', (req, res) => {
  res.render('admin-produk-form', { title: 'Tambah Produk', product: {}, categories: categories(), errors: [], action: '/admin/produk' });
});

router.post('/admin/produk', uploadFoto, (req, res) => {
  const { f, errors } = parseForm(req.body);
  if (req.uploadError) errors.push(req.uploadError);
  if (errors.length) {
    removeUploaded(req.file);
    return res.status(400).render('admin-produk-form', { title: 'Tambah Produk', product: f, categories: categories(), errors, action: '/admin/produk' });
  }
  f.image_url = req.file ? 'assets/gambar/' + req.file.filename : '';
  db.prepare(
    `INSERT INTO products (category_id,name,brand,description,specs,warranty,price,stock,weight_gram,image_url)
     VALUES (@category_id,@name,@brand,@description,@specs,@warranty,@price,@stock,@weight_gram,@image_url)`
  ).run(f);
  req.session.flash = { type: 'success', text: 'Produk berhasil ditambahkan.' };
  res.redirect('/admin/produk');
});

router.get('/admin/produk/:id/edit', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const product = db.prepare('SELECT * FROM products WHERE id=?').get(id);
  if (!product) {
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/admin/produk');
  }
  res.render('admin-produk-form', { title: 'Ubah Produk', product, categories: categories(), errors: [], action: `/admin/produk/${id}` });
});

router.post('/admin/produk/:id', uploadFoto, (req, res) => {
  const id = parseInt(req.params.id, 10);
  const old = db.prepare('SELECT id, image_url FROM products WHERE id=?').get(id);
  if (!old) {
    removeUploaded(req.file);
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/admin/produk');
  }
  const { f, errors } = parseForm(req.body);
  if (req.uploadError) errors.push(req.uploadError);
  if (errors.length) {
    removeUploaded(req.file);
    return res.status(400).render('admin-produk-form', { title: 'Ubah Produk', product: { ...f, id, image_url: old.image_url }, categories: categories(), errors, action: `/admin/produk/${id}` });
  }
  f.image_url = req.file ? 'assets/gambar/' + req.file.filename : old.image_url; // tanpa file baru = foto lama dipertahankan
  db.prepare(
    `UPDATE products SET category_id=@category_id, name=@name, brand=@brand, description=@description,
       specs=@specs, warranty=@warranty, price=@price, stock=@stock, weight_gram=@weight_gram, image_url=@image_url
     WHERE id=@id`
  ).run({ ...f, id });
  if (req.file) removeImage(old.image_url); // foto diganti: hapus file lama
  req.session.flash = { type: 'success', text: 'Produk berhasil diperbarui.' };
  res.redirect('/admin/produk');
});

router.post('/admin/produk/:id/hapus', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const old = db.prepare('SELECT image_url FROM products WHERE id=?').get(id);
  db.prepare('DELETE FROM products WHERE id=?').run(id);
  if (old) removeImage(old.image_url); // hapus file foto bersama produknya
  req.session.flash = { type: 'success', text: 'Produk dihapus.' };
  res.redirect('/admin/produk');
});

module.exports = router;
```

### `fitur/04-crud-produk/admin-produk-list.ejs`

```html
<%- include('partials/header') %>
<div class="row between">
  <h1>Kelola Produk</h1>
  <a class="btn" href="/admin/produk/baru">+ Tambah Produk</a>
</div>
<table class="table">
  <thead><tr><th>ID</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Berat (g)</th><th>Aksi</th></tr></thead>
  <tbody>
  <% products.forEach(p => { %>
    <tr>
      <td><%= p.id %></td>
      <td><%= p.name %><br><small class="muted"><%= p.brand || '' %></small></td>
      <td><%= p.category_name %></td>
      <td><%= rupiah(p.price) %></td>
      <td><%= p.stock %></td>
      <td><%= p.weight_gram %></td>
      <td class="row">
        <a class="btn small" href="/admin/produk/<%= p.id %>/edit">Ubah</a>
        <form method="post" action="/admin/produk/<%= p.id %>/hapus" onsubmit="return confirm('Hapus produk ini?')">
          <button class="btn small danger">Hapus</button>
        </form>
      </td>
    </tr>
  <% }) %>
  </tbody>
</table>
<%- include('partials/footer') %>
```

### `fitur/04-crud-produk/admin-produk-form.ejs`

```html
<%- include('partials/header') %>
<h1><%= title %></h1>
<% if (errors.length) { %>
  <div class="flash error"><ul><% errors.forEach(e => { %><li><%= e %></li><% }) %></ul></div>
<% } %>
<form method="post" action="<%= action %>" class="form" enctype="multipart/form-data">
  <label>Nama produk <input type="text" name="name" value="<%= product.name || '' %>" required></label>
  <label>Merek <input type="text" name="brand" value="<%= product.brand || '' %>"></label>
  <label>Kategori
    <select name="category_id" required>
      <% categories.forEach(c => { %>
        <option value="<%= c.id %>" <%= product.category_id === c.id ? 'selected' : '' %>><%= c.name %></option>
      <% }) %>
    </select>
  </label>
  <div class="cols">
    <label>Harga (Rp) <input type="number" name="price" min="0" value="<%= product.price ?? '' %>" required></label>
    <label>Stok <input type="number" name="stock" min="0" value="<%= product.stock ?? '' %>" required></label>
    <label>Berat (gram) <input type="number" name="weight_gram" min="1" value="<%= product.weight_gram ?? '' %>" required></label>
  </div>
  <label>Garansi <input type="text" name="warranty" value="<%= product.warranty || '' %>" placeholder="mis. 1 tahun"></label>
  <label>Deskripsi <textarea name="description" rows="3"><%= product.description || '' %></textarea></label>
  <label>Spesifikasi (satu baris satu poin) <textarea name="specs" rows="4"><%= product.specs || '' %></textarea></label>
  <%# Field foto sengaja di paling bawah: field teks di atasnya tetap terbaca bila upload gagal %>
  <label>Foto produk (JPG, PNG, atau WEBP, maks. 2 MB)
    <input type="file" name="foto" accept="image/jpeg,image/png,image/webp">
  </label>
  <% if (product.image_url) { %>
    <div>
      <img src="<%= imgSrc(product.image_url) %>" alt="Foto saat ini" class="preview">
      <small class="muted">Foto saat ini: <%= product.image_url %>. Pilih file baru hanya jika ingin menggantinya.</small>
    </div>
  <% } %>
  <div class="row">
    <button class="btn">Simpan</button>
    <a class="btn ghost" href="/admin/produk">Batal</a>
  </div>
</form>
<%- include('partials/footer') %>
```

---

## Fitur 5 — Katalog Produk

**Kartu:** PPL-03 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Halaman beranda menampilkan semua produk dalam bentuk kartu: foto (atau kotak "Tanpa foto"), nama, harga, kategori, merek, dan stok. Produk dengan stok 0 berlabel **Stok habis**. Boleh dilihat tanpa login.

### Isi folder `fitur/05-katalog/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET `/`, mengambil produk dari database |
| `katalog.ejs` | Tampilan grid kartu produk |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /`

### Bergantung pada

Fondasi saja. Titik sambung opsional dengan fitur 6: bila ada, katalog otomatis memakai pencarian dan filternya (dijelaskan di komentar `index.js`).

### Cara mencoba

- Customer atau tamu: buka `http://localhost:3000/`.
- Admin: produk baru dari fitur 4 langsung terlihat di sini.

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/` tanpa login | Semua produk tampil dengan nama, harga, kategori, merek |
| 2 | Lihat "LAN Tester RJ45" | Berlabel "Stok habis" |
| 3 | Perkecil jendela browser ke lebar HP | Kartu tersusun rapi |
| 4 | Lihat produk tanpa foto | Tampil kotak "Tanpa foto" |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-03-katalog
# taruh folder fitur/05-katalog/ di project, lalu uji: npm start
git add fitur/05-katalog
git commit -m "[PPL-03] tampilkan katalog produk"
git push -u origin feature/PPL-03-katalog
# buka Pull Request berjudul: [PPL-03] Katalog produk
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-03 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `05-katalog`
- [ ] Tidak ada file di luar `fitur/05-katalog/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-03` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/05-katalog/index.js`

```js
// FITUR 5 — KATALOG PRODUK (kartu PPL-03)
// Alamat   : GET / (beranda = daftar semua produk, boleh tanpa login)
// Tampilan : katalog.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel products + categories)
//
// Titik sambung dengan fitur 6 (cari-filter), TIDAK wajib ada:
//   - res.locals.filter     = { where: [potongan SQL], params: [nilai] }  -> menyaring query
//   - res.locals.filterForm = HTML form pencarian                          -> ditampilkan di atas daftar
// Kalau fitur 6 belum ada, dua nilai itu kosong dan katalog menampilkan semua produk.
const express = require('express');
const db = require('../../db');

const router = express.Router();

router.get('/', (req, res) => {
  const f = res.locals.filter || { where: [], params: [] };
  const sql = `SELECT p.*, c.name AS category_name
               FROM products p JOIN categories c ON c.id = p.category_id
               ${f.where.length ? 'WHERE ' + f.where.join(' AND ') : ''}
               ORDER BY p.created_at DESC, p.id DESC`;
  const products = db.prepare(sql).all(...f.params);
  res.render('katalog', { title: 'Katalog', products });
});

module.exports = router;
```

### `fitur/05-katalog/katalog.ejs`

```html
<%- include('partials/header') %>
<h1>Katalog Produk</h1>

<%- filterForm %>

<% if (!products.length) { %>
  <p class="empty">Tidak ada produk yang cocok.</p>
<% } else { %>
<div class="grid">
  <% products.forEach(p => { %>
  <article class="card">
    <a href="/produk/<%= p.id %>" class="thumb">
      <% if (p.image_url) { %><img src="<%= imgSrc(p.image_url) %>" alt="<%= p.name %>"><% } else { %><span>Tanpa foto</span><% } %>
    </a>
    <div class="body">
      <small class="muted"><%= p.category_name %><%= p.brand ? ' · ' + p.brand : '' %></small>
      <h3><a href="/produk/<%= p.id %>"><%= p.name %></a></h3>
      <div class="price"><%= rupiah(p.price) %></div>
      <% if (p.stock < 1) { %>
        <span class="badge out">Stok habis</span>
      <% } else { %>
        <small class="muted">Stok: <%= p.stock %></small>
      <% } %>
    </div>
  </article>
  <% }) %>
</div>
<% } %>
<%- include('partials/footer') %>
```

---

## Fitur 6 — Cari dan Filter Kategori

**Kartu:** PPL-04 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Kotak pencarian (nama atau merek) dan dropdown kategori di atas katalog. Keduanya bisa digabung, ada tombol Reset. Fitur ini tidak punya halaman sendiri: ia menyisipkan pencarian ke katalog lewat `middleware.js`.

### Isi folder `fitur/06-cari-filter/`

| File | Fungsi |
|---|---|
| `middleware.js` | Membaca `?q=` dan `?kategori=`, menyiapkan filter SQL dan HTML form untuk katalog |
| `filter-form.ejs` | Potongan tampilan form pencarian |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /?q=kata&kategori=ID (memperluas halaman katalog)`

### Bergantung pada

Fitur 5 (katalog), karena yang ditampilkan dan disaring adalah daftar katalog.

### Cara mencoba

- Customer atau tamu: di beranda ketik kata kunci dan/atau pilih kategori, klik **Cari**; **Reset** untuk membersihkan.

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Pilih kategori Router lalu Cari | Hanya 2 router yang tampil |
| 2 | Cari "crimping" | Hanya Tang Crimping |
| 3 | Cari "TP-Link" dengan kategori Switch | Hanya switch merek TP-Link |
| 4 | Cari "zzzz" | Pesan "Tidak ada produk yang cocok." |
| 5 | Klik Reset | Semua produk kembali |
| 6 | Ketik `<script>alert(1)</script>` di kotak cari | Tidak dijalankan, hanya tampil sebagai teks |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-04-cari-filter
# taruh folder fitur/06-cari-filter/ di project, lalu uji: npm start
git add fitur/06-cari-filter
git commit -m "[PPL-04] tambah pencarian dan filter kategori"
git push -u origin feature/PPL-04-cari-filter
# buka Pull Request berjudul: [PPL-04] Pencarian dan filter kategori
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-04 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `06-cari-filter`
- [ ] Tidak ada file di luar `fitur/06-cari-filter/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-04` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/06-cari-filter/middleware.js`

```js
// FITUR 6 — CARI DAN FILTER KATEGORI (kartu PPL-04)
// Fitur ini TIDAK punya halaman sendiri. Ia "menyisipkan" pencarian ke katalog (fitur 5)
// lewat dua nilai di res.locals, lalu katalog memakainya:
//   res.locals.filter     = { where, params }  potongan SQL untuk menyaring produk
//   res.locals.filterForm = HTML kotak cari + dropdown kategori (dari filter-form.ejs)
// File ini dimuat lebih dulu dari semua index.js (lihat muat-fitur.js).
// Alamat   : GET /?q=kata&kategori=ID
// Memakai  : fondasi saja (db.js tabel categories)
const express = require('express');
const fs = require('fs');
const path = require('path');
const ejs = require('ejs');
const db = require('../../db');

const router = express.Router();
const formTemplate = fs.readFileSync(path.join(__dirname, 'filter-form.ejs'), 'utf8');

router.get('/', (req, res, next) => {
  const q = (req.query.q || '').trim();
  const kategori = parseInt(req.query.kategori, 10) || null;

  // Nilai pencarian dikirim sebagai parameter (tanda ?), bukan disambung ke teks SQL,
  // sehingga aman dari SQL injection.
  const where = [];
  const params = [];
  if (q) {
    where.push('(p.name LIKE ? OR p.brand LIKE ?)');
    params.push(`%${q}%`, `%${q}%`);
  }
  if (kategori) {
    where.push('p.category_id = ?');
    params.push(kategori);
  }
  res.locals.filter = { where, params };

  const categories = db.prepare('SELECT * FROM categories ORDER BY name').all();
  res.locals.filterForm = ejs.render(formTemplate, { q, kategori, categories });
  next(); // lanjut ke katalog (fitur 5)
});

module.exports = router;
```

### `fitur/06-cari-filter/filter-form.ejs`

```html
<form method="get" action="/" class="filters">
  <input type="text" name="q" value="<%= q %>" placeholder="Cari nama atau merek...">
  <select name="kategori">
    <option value="">Semua kategori</option>
    <% categories.forEach(c => { %>
      <option value="<%= c.id %>" <%= kategori === c.id ? 'selected' : '' %>><%= c.name %></option>
    <% }) %>
  </select>
  <button class="btn">Cari</button>
  <% if (q || kategori) { %><a href="/" class="btn ghost">Reset</a><% } %>
</form>
```

---

## Fitur 7 — Detail Produk

**Kartu:** PPL-05 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Halaman satu produk: deskripsi, spesifikasi (satu baris menjadi satu poin), merek, garansi, berat, stok, harga, dan tombol **Tambah ke keranjang**. Tamu melihat "Masuk untuk membeli"; stok 0 menampilkan label Stok habis.

### Isi folder `fitur/07-detail-produk/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET `/produk/:id` |
| `detail-produk.ejs` | Tampilan halaman detail |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /produk/:id`

### Bergantung pada

Fondasi saja (bisa dibuka lewat alamat langsung). Tautan menuju halaman ini ada di fitur 5. Tombol beli baru berfungsi setelah fitur 8.

### Cara mencoba

- Customer atau tamu: klik nama produk di katalog, atau buka `http://localhost:3000/produk/1`.

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/produk/1` sebagai tamu | Detail lengkap; tombol "Masuk untuk membeli" |
| 2 | Buka `/produk/1` sebagai pembeli | Form jumlah dan tombol "Tambah ke keranjang" |
| 3 | Buka `/produk/10` (stok 0) | Label "Stok habis", tanpa tombol beli |
| 4 | Buka `/produk/9999` | Halaman "Produk tidak ditemukan" (404) |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-05-detail-produk
# taruh folder fitur/07-detail-produk/ di project, lalu uji: npm start
git add fitur/07-detail-produk
git commit -m "[PPL-05] tambah halaman detail produk"
git push -u origin feature/PPL-05-detail-produk
# buka Pull Request berjudul: [PPL-05] Detail produk
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-05 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `07-detail-produk`
- [ ] Tidak ada file di luar `fitur/07-detail-produk/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-05` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/07-detail-produk/index.js`

```js
// FITUR 7 — DETAIL PRODUK (kartu PPL-05)
// Alamat   : GET /produk/:id
// Tampilan : detail-produk.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel products + categories)
// Catatan  : tombol "Tambah ke keranjang" di halaman ini mengirim ke fitur 8 (keranjang);
//            tombolnya baru berfungsi setelah fitur 8 digabung.
const express = require('express');
const db = require('../../db');

const router = express.Router();

router.get('/produk/:id', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const product = db
    .prepare(
      `SELECT p.*, c.name AS category_name
       FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?`
    )
    .get(id);
  if (!product) {
    return res.status(404).render('error', { title: 'Tidak ditemukan', message: 'Produk tidak ditemukan.' });
  }
  res.render('detail-produk', { title: product.name, product });
});

module.exports = router;
```

### `fitur/07-detail-produk/detail-produk.ejs`

```html
<%- include('partials/header') %>
<p><a href="/">&larr; Kembali ke katalog</a></p>
<div class="detail">
  <div class="pic">
    <% if (product.image_url) { %><img src="<%= imgSrc(product.image_url) %>" alt="<%= product.name %>"><% } else { %><span>Tanpa foto</span><% } %>
  </div>
  <div class="info">
    <small class="muted"><%= product.category_name %></small>
    <h1><%= product.name %></h1>
    <p class="muted">Merek: <%= product.brand || '-' %> · Garansi: <%= product.warranty || '-' %></p>
    <div class="price big"><%= rupiah(product.price) %></div>
    <p>Berat: <%= product.weight_gram %> gram</p>
    <% if (product.stock < 1) { %>
      <span class="badge out">Stok habis</span>
    <% } else { %>
      <p>Stok tersedia: <strong><%= product.stock %></strong></p>
      <% if (!user) { %>
        <p><a class="btn" href="/login">Masuk untuk membeli</a></p>
      <% } else if (user.role === 'buyer') { %>
        <form method="post" action="/keranjang/tambah" class="row">
          <input type="hidden" name="product_id" value="<%= product.id %>">
          <input type="number" name="qty" value="1" min="1" max="<%= product.stock %>">
          <button class="btn">Tambah ke keranjang</button>
        </form>
      <% } %>
    <% } %>
    <h3>Deskripsi</h3>
    <p><%= product.description || '-' %></p>
    <h3>Spesifikasi</h3>
    <% if (product.specs) { %>
      <ul>
        <% product.specs.split('\n').filter(Boolean).forEach(s => { %><li><%= s %></li><% }) %>
      </ul>
    <% } else { %><p>-</p><% } %>
  </div>
</div>
<%- include('partials/footer') %>
```

---

## Fitur 8 — Keranjang Belanja

**Kartu:** PPL-06 · **Ukuran:** Besar · **Pemegang:** (isi nama)

Pembeli menambah produk, mengubah jumlah, dan menghapus item. Jumlah otomatis dibatasi sesuai stok. Total harga dan total berat dihitung otomatis, dan data tersimpan per akun di database. Hanya untuk akun pembeli.

### Isi folder `fitur/08-keranjang/`

| File | Fungsi |
|---|---|
| `index.js` | Route keranjang: tampil, tambah, ubah, hapus, dengan batas stok |
| `keranjang.ejs` | Tampilan tabel keranjang |
| `README.md` | Catatan ini |

### Alamat (route)

- `GET /keranjang`
- `POST /keranjang/tambah`
- `POST /keranjang/ubah`
- `POST /keranjang/hapus`

### Bergantung pada

Fondasi (tabel `cart_items` dan angka keranjang di menu atas sudah di fondasi). Untuk dicoba butuh akun pembeli (fitur 1 dan 2) dan tombol beli di fitur 7.

### Cara mencoba

- Customer: login, buka detail produk, **Tambah ke keranjang**, lalu menu **Keranjang**.
- Admin: tidak memakai keranjang (diarahkan ke beranda).

### Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/keranjang` tanpa login | Diarahkan ke login |
| 2 | Tambah Tang Crimping jumlah 2 | Masuk keranjang; menu atas "Keranjang (2)" |
| 3 | Tambah lagi 5 (stok hanya 3) | Jumlah dibatasi 3, muncul pesan batas stok |
| 4 | Tambah LAN Tester (stok 0) | Ditolak: "Stok produk habis." |
| 5 | Ubah jumlah menjadi 1 | Subtotal dan total ikut berubah |
| 6 | Ubah ke 0 atau klik Hapus | Item hilang; keranjang kosong menampilkan pesan |
| 7 | Login sebagai admin lalu buka `/keranjang` | Ditolak, diarahkan ke beranda |

### Git

```bash
git checkout main && git pull
git checkout -b feature/PPL-06-keranjang
# taruh folder fitur/08-keranjang/ di project, lalu uji: npm start
git add fitur/08-keranjang
git commit -m "[PPL-06] tambah keranjang belanja"
git push -u origin feature/PPL-06-keranjang
# buka Pull Request berjudul: [PPL-06] Keranjang belanja
# minta review minimal 1 anggota lain, merge ke main, lalu pindahkan kartu PPL-06 ke Done
```

### Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `08-keranjang`
- [ ] Tidak ada file di luar `fitur/08-keranjang/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-06` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file

### `fitur/08-keranjang/index.js`

```js
// FITUR 8 — KERANJANG BELANJA (kartu PPL-06)
// Alamat   : GET /keranjang, POST /keranjang/tambah, /keranjang/ubah, /keranjang/hapus
// Tampilan : keranjang.ejs (di folder ini)
// Memakai  : fondasi (db.js tabel cart_items, middleware/auth.js). Hanya untuk akun pembeli.
// Catatan  : tombol "Tambah ke keranjang" ada di halaman detail produk (fitur 7).
//            Angka keranjang di menu atas dihitung di server.js (fondasi).
const express = require('express');
const db = require('../../db');
const { requireBuyer } = require('../../middleware/auth');

const router = express.Router();
router.use('/keranjang', requireBuyer); // semua alamat /keranjang... hanya untuk pembeli

router.get('/keranjang', (req, res) => {
  const items = db
    .prepare(
      `SELECT ci.id, ci.qty, p.id AS product_id, p.name, p.brand, p.price, p.stock, p.weight_gram, p.image_url
       FROM cart_items ci JOIN products p ON p.id = ci.product_id
       WHERE ci.user_id = ? ORDER BY ci.id`
    )
    .all(req.session.user.id);
  const total = items.reduce((s, i) => s + i.price * i.qty, 0);
  const totalWeight = items.reduce((s, i) => s + i.weight_gram * i.qty, 0);
  res.render('keranjang', { title: 'Keranjang', items, total, totalWeight });
});

router.post('/keranjang/tambah', (req, res) => {
  const userId = req.session.user.id;
  const productId = parseInt(req.body.product_id, 10);
  const qty = Math.max(1, parseInt(req.body.qty, 10) || 1);
  const back = req.get('Referer') || '/';

  const product = db.prepare('SELECT * FROM products WHERE id=?').get(productId);
  if (!product) {
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/');
  }
  if (product.stock < 1) {
    req.session.flash = { type: 'error', text: 'Stok produk habis.' };
    return res.redirect(back);
  }

  const existing = db
    .prepare('SELECT qty FROM cart_items WHERE user_id=? AND product_id=?')
    .get(userId, productId);
  const wanted = (existing ? existing.qty : 0) + qty;
  const finalQty = Math.min(wanted, product.stock);

  db.prepare(
    `INSERT INTO cart_items (user_id, product_id, qty) VALUES (?,?,?)
     ON CONFLICT(user_id, product_id) DO UPDATE SET qty = excluded.qty`
  ).run(userId, productId, finalQty);

  req.session.flash =
    finalQty < wanted
      ? { type: 'error', text: `Jumlah dibatasi sesuai stok (${product.stock}).` }
      : { type: 'success', text: 'Produk ditambahkan ke keranjang.' };
  res.redirect(back);
});

router.post('/keranjang/ubah', (req, res) => {
  const userId = req.session.user.id;
  const productId = parseInt(req.body.product_id, 10);
  const qty = parseInt(req.body.qty, 10);
  const product = db.prepare('SELECT stock FROM products WHERE id=?').get(productId);

  if (!product || Number.isNaN(qty)) {
    req.session.flash = { type: 'error', text: 'Data tidak valid.' };
    return res.redirect('/keranjang');
  }
  if (qty <= 0) {
    db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(userId, productId);
    req.session.flash = { type: 'success', text: 'Item dihapus dari keranjang.' };
    return res.redirect('/keranjang');
  }
  const finalQty = Math.min(qty, product.stock);
  if (finalQty < 1) {
    db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(userId, productId);
  } else {
    db.prepare('UPDATE cart_items SET qty=? WHERE user_id=? AND product_id=?').run(finalQty, userId, productId);
  }
  req.session.flash =
    finalQty < qty
      ? { type: 'error', text: `Jumlah dibatasi sesuai stok (${product.stock}).` }
      : { type: 'success', text: 'Keranjang diperbarui.' };
  res.redirect('/keranjang');
});

router.post('/keranjang/hapus', (req, res) => {
  const productId = parseInt(req.body.product_id, 10);
  db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(req.session.user.id, productId);
  req.session.flash = { type: 'success', text: 'Item dihapus dari keranjang.' };
  res.redirect('/keranjang');
});

module.exports = router;
```

### `fitur/08-keranjang/keranjang.ejs`

```html
<%- include('partials/header') %>
<h1>Keranjang Belanja</h1>
<% if (!items.length) { %>
  <p class="empty">Keranjang masih kosong. <a href="/">Lihat katalog</a></p>
<% } else { %>
<table class="table">
  <thead><tr><th>Produk</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th><th></th></tr></thead>
  <tbody>
  <% items.forEach(i => { %>
    <tr>
      <td>
        <a href="/produk/<%= i.product_id %>"><%= i.name %></a><br>
        <small class="muted"><%= i.brand || '' %> · Stok <%= i.stock %></small>
      </td>
      <td><%= rupiah(i.price) %></td>
      <td>
        <form method="post" action="/keranjang/ubah" class="row">
          <input type="hidden" name="product_id" value="<%= i.product_id %>">
          <input type="number" name="qty" value="<%= i.qty %>" min="0" max="<%= i.stock %>">
          <button class="btn small">Ubah</button>
        </form>
      </td>
      <td><%= rupiah(i.price * i.qty) %></td>
      <td>
        <form method="post" action="/keranjang/hapus">
          <input type="hidden" name="product_id" value="<%= i.product_id %>">
          <button class="btn small danger">Hapus</button>
        </form>
      </td>
    </tr>
  <% }) %>
  </tbody>
  <tfoot>
    <tr><td colspan="3" class="right">Total berat</td><td colspan="2"><%= totalWeight %> gram</td></tr>
    <tr><td colspan="3" class="right"><strong>Total</strong></td><td colspan="2"><strong><%= rupiah(total) %></strong></td></tr>
  </tfoot>
</table>
<p class="muted">Checkout dan pembayaran dikerjakan pada Sprint 2.</p>
<% } %>
<%- include('partials/footer') %>
```

---

# BAGIAN E — ALUR GIT UNTUK ANGGOTA TIM

Langkah ini sama untuk setiap anggota. Ganti `<folder>`, `<branch>`, `<commit>` dengan data fitur Anda (tabel B.3 dan Bagian D).

1. **Ambil repo terbaru** (setelah fondasi di-merge):
   ```bash
   git clone <alamat-repo-github> && cd toko-jaringan
   npm install
   cp .env.example .env     # Windows CMD: copy .env.example .env, lalu isi nilainya
   ```
2. **Buat branch** dari `main`: `git checkout -b <branch>`
3. **Taruh folder fitur** Anda (`fitur/<folder>/`) ke dalam project, lalu jalankan `npm start` dan uji dengan tabel Uji.
4. **Commit hanya folder Anda**:
   ```bash
   git add fitur/<folder>
   git commit -m "<commit>"
   git push -u origin <branch>
   ```
5. **Buka Pull Request** di GitHub dengan judul memuat kode kartu, minta review minimal 1 anggota lain, lalu merge setelah disetujui.
6. **Pindahkan kartu ke Done** di board setelah PR ter-merge dan lulus uji.

Aturan penting:
- Jangan mengubah file di luar folder fitur Anda. Jika fondasi perlu diubah, buat kartu terpisah dan minta pemegang fondasi.
- Kode kartu (PPL-xx) harus sama di kartu, branch, commit, dan judul PR.
- Jangan commit `.env`, `data.sqlite`, atau `node_modules`.
- Foto produk yang diunggah lewat fitur 4 ada di `public/assets/gambar/`. Commit foto yang dipakai agar semua anggota melihat gambar yang sama.
