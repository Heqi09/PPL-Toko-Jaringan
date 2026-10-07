# Rincian Kartu Sprint 1 — Toko Jaringan (PPL-01 s.d. PPL-06)

**Tujuan Sprint 1:** pembeli bisa registrasi/login, melihat dan mencari katalog toko jaringan, serta memakai keranjang; admin bisa mengelola produk.

**Aturan semua kartu** (sesuai panduan PPL):
- Kode kartu sama di: judul kartu, nama branch, pesan commit, dan judul PR.
- Branch: `feature/PPL-xx-nama-singkat` · Commit: `[PPL-xx] pesan` · PR: `[PPL-xx] judul`
- PR wajib di-review minimal 1 anggota lain sebelum merge ke `main`.
- Kartu pindah ke **Done** hanya setelah PR ter-merge dan lulus QA (Definition of Done).
- Label kartu: `Sprint 1`.

Peta file kode (project di folder `toko-jaringan/`):

| Kartu | Folder / file |
|---|---|
| PPL-01 | `fitur/01-registrasi/`, `fitur/02-login/`, `fitur/03-logout/` (tabel `users` ada di `db.js`, pengaman halaman di `middleware/auth.js`) |
| PPL-02 | `fitur/04-crud-produk/` (tabel `products` dan `categories` ada di `db.js`) |
| PPL-03 | `fitur/05-katalog/` |
| PPL-04 | `fitur/06-cari-filter/` (menyisipkan pencarian ke katalog lewat `middleware.js`) |
| PPL-05 | `fitur/07-detail-produk/` |
| PPL-06 | `fitur/08-keranjang/` (tabel `cart_items` ada di `db.js`) |
| Dasar (PPL-24) | `server.js`, `muat-fitur.js`, `db.js`, `middleware/`, `views/`, `public/`, `.env.example`, `.gitignore` |

Cara folder-folder ini dibagi ke anggota tim dan digabung ke GitHub: lihat `docs/PANDUAN-PER-FITUR.md`. Setiap folder fitur juga punya `README.md` sendiri.

---

## PPL-01 — Registrasi dan Login Pembeli

**User story:** Sebagai pembeli, saya ingin registrasi dan login, agar pesanan saya tersimpan di akun saya.
**Prioritas:** Tinggi · **Penanggung jawab utama:** Backend · **Pendukung:** UI/UX (desain form), Frontend (view), QA

**Kriteria penerimaan**
1. Registrasi dengan nama, email, password (min. 8 karakter) dan konfirmasi password.
2. Password disimpan ter-hash (bcrypt), bukan teks asli.
3. Email duplikat dan format email salah ditolak dengan pesan jelas.
4. Login dan logout berfungsi; akun admin diarahkan ke halaman kelola produk.

**Subtask (checklist di kartu)**
- [ ] Desain form login dan registrasi (UI/UX)
- [ ] Tabel `users` + seed akun admin dari `.env` (Backend)
- [ ] Route `POST /register` dengan validasi (Backend)
- [ ] Route `POST /login`, `POST /logout`, regenerasi session saat login (Backend)
- [ ] View `register.ejs` dan `login.ejs` (Frontend)
- [ ] Middleware `requireLogin`, `requireBuyer`, `requireAdmin` (Backend)
- [ ] Skenario uji tertulis dan eksekusi (QA)

**Git:** dikerjakan tiga orang dengan tiga PR terpisah, kode kartu sama (PPL-01): `feature/PPL-01-registrasi`, `feature/PPL-01-login`, `feature/PPL-01-logout` (rincian di `docs/PANDUAN-PER-FITUR.md`)

**Skenario uji (QA)**

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Daftar dengan email `bukan-email` | Ditolak, pesan "Format email tidak valid" |
| 2 | Daftar dengan password 3 karakter | Ditolak, pesan "Password minimal 8 karakter" |
| 3 | Daftar dengan data benar | Diarahkan ke login, muncul pesan sukses |
| 4 | Daftar ulang dengan email sama | Ditolak, "Email sudah terdaftar" |
| 5 | Login dengan password salah | Pesan "Email atau password salah" |
| 6 | Login benar lalu klik Keluar | Masuk, lalu kembali ke status belum login |
| 7 | Cek tabel `users` | Kolom `password_hash` berisi hash (diawali `$2a$`/`$2b$`) |

---

## PPL-02 — Kelola Produk oleh Admin (CRUD)

**User story:** Sebagai admin, saya ingin menambah, mengubah, dan menghapus produk beserta harga, stok, dan berat, agar katalog toko selalu akurat.
**Prioritas:** Tinggi · **Penanggung jawab utama:** Backend · **Pendukung:** Frontend, UI/UX, QA

**Kriteria penerimaan**
1. CRUD produk berjalan: tambah, ubah, hapus, daftar.
2. Ada kolom nama, merek, kategori, harga, stok, **berat (gram)**, garansi, deskripsi, spesifikasi, URL gambar.
3. Hanya admin yang dapat mengakses halaman ini (pembeli mendapat 403, tamu diarahkan ke login).
4. Validasi: harga/stok tidak negatif, berat lebih dari 0.

**Catatan penting:** kolom **berat** wajib ada sekarang karena dipakai hitung ongkir di Sprint 3.

**Subtask**
- [ ] Skema `categories` dan `products` + data contoh (Backend)
- [ ] Route daftar, tambah, ubah, hapus di `fitur/04-crud-produk/index.js` (Backend)
- [ ] Fungsi `parseForm` validasi input (Backend)
- [ ] View `admin/list.ejs` dan `admin/form.ejs` (Frontend)
- [ ] Desain halaman kelola produk (UI/UX)
- [ ] Uji akses pembeli/tamu ke halaman admin (QA)

**Git:** branch `feature/PPL-02-crud-produk` · commit `[PPL-02] tambah CRUD produk admin` · PR `[PPL-02] Kelola produk admin`

**Skenario uji (QA)**

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/admin/produk` tanpa login | Diarahkan ke `/login` |
| 2 | Buka `/admin/produk` sebagai pembeli | Halaman "Akses ditolak" (403) |
| 3 | Login admin, tambah produk berat 0 | Ditolak, pesan validasi berat |
| 4 | Tambah produk data benar | Muncul di daftar admin dan di katalog |
| 5 | Ubah harga produk | Harga baru tampil di halaman detail |
| 6 | Hapus produk | Hilang dari daftar; membuka detailnya menghasilkan 404 |

---

## PPL-03 — Katalog Produk

**User story:** Sebagai pembeli, saya ingin melihat katalog produk, agar saya tahu barang yang dijual.
**Prioritas:** Tinggi · **Penanggung jawab utama:** Frontend · **Pendukung:** UI/UX, Backend, QA

**Kriteria penerimaan**
1. Katalog menampilkan foto (atau placeholder), nama, harga, kategori/merek.
2. Produk dengan stok 0 ditandai "Stok habis".
3. Katalog dapat dilihat tanpa login.

**Subtask**
- [ ] Desain kartu produk dan grid (UI/UX)
- [ ] Query daftar produk join kategori (Backend)
- [ ] View `index.ejs` + `public/style.css` (Frontend)
- [ ] Uji tampilan di layar HP dan desktop (QA)

**Git:** branch `feature/PPL-03-katalog` · commit `[PPL-03] tampilkan katalog produk` · PR `[PPL-03] Katalog produk`

**Skenario uji:** buka `/` tanpa login → semua produk tampil; produk "LAN Tester RJ45" (stok 0) berlabel "Stok habis"; tampilan rapi di lebar layar HP.

---

## PPL-04 — Cari dan Filter Kategori

**User story:** Sebagai pembeli, saya ingin mencari dan memfilter produk menurut kategori, agar cepat menemukan barang.
**Prioritas:** Sedang · **Penanggung jawab utama:** Frontend · **Pendukung:** Backend, QA

**Kriteria penerimaan**
1. Filter kategori (Router, Switch, Access Point, Kabel, Aksesoris) berfungsi.
2. Pencarian berdasarkan nama atau merek berfungsi.
3. Pencarian dan filter bisa digabung; tombol Reset tersedia; pesan jika tidak ada hasil.

**Subtask**
- [ ] Query dinamis `WHERE` dengan parameter terikat (aman dari SQL injection) (Backend)
- [ ] Form pencarian + dropdown kategori (Frontend)
- [ ] Pesan "Tidak ada produk yang cocok" (Frontend)
- [ ] Uji kombinasi kata kunci + kategori (QA)

**Git:** branch `feature/PPL-04-cari-filter` · commit `[PPL-04] tambah pencarian dan filter kategori` · PR `[PPL-04] Pencarian dan filter kategori`

**Skenario uji**

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Pilih kategori Router | Hanya router yang tampil |
| 2 | Cari "crimping" | Hanya Tang Crimping |
| 3 | Cari "TP-Link" + kategori Switch | Hanya switch merek TP-Link |
| 4 | Cari "zzzz" | Pesan tidak ada produk yang cocok |

---

## PPL-05 — Detail Produk

**User story:** Sebagai pembeli, saya ingin melihat detail produk (spesifikasi, merek, garansi), agar yakin sebelum membeli.
**Prioritas:** Sedang · **Penanggung jawab utama:** Frontend · **Pendukung:** Backend, UI/UX, QA

**Kriteria penerimaan**
1. Halaman detail menampilkan deskripsi, spesifikasi, merek, garansi, berat, stok, harga.
2. Ada tombol "Tambah ke keranjang" (jumlah bisa dipilih) untuk pembeli yang login.
3. Tamu melihat tombol "Masuk untuk membeli"; stok habis menampilkan label, tanpa tombol beli.
4. ID produk tidak ada menghasilkan halaman 404.

**Subtask**
- [ ] Route `GET /produk/:id` (Backend)
- [ ] Desain halaman detail (UI/UX)
- [ ] View `product.ejs`, spesifikasi per baris jadi daftar poin (Frontend)
- [ ] Uji tiga kondisi: tamu, pembeli, stok habis (QA)

**Git:** branch `feature/PPL-05-detail-produk` · commit `[PPL-05] tambah halaman detail produk` · PR `[PPL-05] Detail produk`

**Skenario uji:** buka `/produk/1` sebagai tamu (tombol masuk), sebagai pembeli (tombol tambah), `/produk/10` (stok habis), `/produk/9999` (404).

---

## PPL-06 — Keranjang Belanja

**User story:** Sebagai pembeli, saya ingin memakai keranjang belanja, agar bisa membeli beberapa barang sekaligus.
**Prioritas:** Tinggi · **Penanggung jawab utama:** Backend + Frontend · **Pendukung:** QA

**Kriteria penerimaan**
1. Tambah, ubah jumlah, dan hapus item.
2. Jumlah tidak boleh melebihi stok (otomatis dibatasi, ada pesan).
3. Total harga dan total berat dihitung otomatis.
4. Keranjang hanya untuk pembeli yang login; data tersimpan per akun di database.

**Subtask**
- [ ] Tabel `cart_items` dengan `UNIQUE(user_id, product_id)` (Backend)
- [ ] Route `/keranjang/tambah`, `/ubah`, `/hapus` dengan batas stok (Backend)
- [ ] View `cart.ejs` + angka keranjang di navbar (Frontend)
- [ ] Desain halaman keranjang (UI/UX)
- [ ] Uji batas stok, qty 0, dan akses tanpa login (QA)

**Git:** branch `feature/PPL-06-keranjang` · commit `[PPL-06] tambah keranjang belanja` · PR `[PPL-06] Keranjang belanja`

**Skenario uji**

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/keranjang` tanpa login | Diarahkan ke login |
| 2 | Tambah Tang Crimping qty 2 | Masuk keranjang, navbar menunjukkan 2 |
| 3 | Tambah lagi qty 5 (stok 3) | Jumlah dibatasi 3, ada pesan batas stok |
| 4 | Tambah LAN Tester (stok 0) | Ditolak, "Stok produk habis" |
| 5 | Ubah jumlah jadi 1 | Subtotal dan total ikut berubah |
| 6 | Ubah jumlah jadi 0 atau klik Hapus | Item hilang; keranjang kosong menampilkan pesan |
| 7 | Login sebagai admin lalu buka `/keranjang` | Ditolak, diarahkan ke beranda |

---

## Kartu Pendukung Sprint 1 (non-kode dan setup)

Kode PPL-24 s.d. PPL-30 dipakai agar semua anggota punya kartu. Tambahkan ke backlog.

| Kode | Judul | Penanggung jawab | Isi pekerjaan | Bukti |
|---|---|---|---|---|
| PPL-24 | Setup struktur project dan `.env` | Backend | Inisialisasi project, `package.json`, `.env.example`, `.gitignore`, `server.js` | Commit + PR |
| PPL-25 | Desain UI katalog, detail, keranjang | UI/UX | Wireframe/mockup Figma, tautan dicantumkan di README | Tautan desain di board |
| PPL-26 | Skenario dan laporan uji Sprint 1 | QA | Tabel skenario uji PPL-01 s.d. PPL-06 (file Markdown di repo), bug jadi kartu | File `docs/uji-sprint-1.md` |
| PPL-27 | README dan catatan sprint | Dokumentasi | README (deskripsi, cara jalan, anggota-peran), catatan planning/review/retro | PR dokumen + Wiki |
| PPL-28 | Draf Laporan Requirement bab 1-2 | Product Owner | Isi Introduction dan Overall Description sesuai template IEEE | Dokumen di Drive/repo |
| PPL-29 | Rekap rencana vs realisasi Sprint 1 | Scrum Master | Isi Tabel 7 di akhir sprint | Catatan sprint |
| PPL-30 | Persiapan demo integrasi Monev | Scrum Master + 1 developer | Latih skenario 5 langkah (kartu → branch → commit → PR → review → Done) | Notifikasi di kanal |

---

## Cara Menjalankan Alur Kerja (contoh nyata: PPL-04)

```bash
git checkout main && git pull
git checkout -b feature/PPL-04-cari-filter
# ... kerjakan kode ...
git add fitur/06-cari-filter
git commit -m "[PPL-04] tambah pencarian dan filter kategori"
git push -u origin feature/PPL-04-cari-filter
# buka Pull Request di GitHub, judul: [PPL-04] Pencarian dan filter kategori
# anggota lain review -> Approve -> Merge
# pindahkan kartu PPL-04 ke Done
```

## Definition of Done (centang sebelum kartu ke Done)

- [ ] Semua kriteria penerimaan kartu terpenuhi
- [ ] Kode di-merge ke `main` lewat PR yang di-review anggota lain
- [ ] Kode PPL-xx ada di branch, commit, dan judul PR
- [ ] Sudah diuji QA dan tidak ada error saat dijalankan
- [ ] Tidak ada API key atau password di repo (`.env` tidak ter-commit)
