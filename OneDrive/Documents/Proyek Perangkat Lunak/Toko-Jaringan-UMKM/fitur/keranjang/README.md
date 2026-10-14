# Fitur 8 — Keranjang Belanja

**Kartu:** PPL-06 · **Ukuran:** Besar · **Pemegang:** (isi nama)

Pembeli menambah produk, mengubah jumlah, dan menghapus item. Jumlah otomatis dibatasi sesuai stok. Total harga dan total berat dihitung otomatis, dan data tersimpan per akun di database. Hanya untuk akun pembeli.

## Isi folder `fitur/08-keranjang/`

| File | Fungsi |
|---|---|
| `index.js` | Route keranjang: tampil, tambah, ubah, hapus, dengan batas stok |
| `keranjang.ejs` | Tampilan tabel keranjang |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /keranjang`
- `POST /keranjang/tambah`
- `POST /keranjang/ubah`
- `POST /keranjang/hapus`

## Bergantung pada

Fondasi (tabel `cart_items` dan angka keranjang di menu atas sudah di fondasi). Untuk dicoba butuh akun pembeli (fitur 1 dan 2) dan tombol beli di fitur 7.

## Cara mencoba

- Customer: login, buka detail produk, **Tambah ke keranjang**, lalu menu **Keranjang**.
- Admin: tidak memakai keranjang (diarahkan ke beranda).

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/keranjang` tanpa login | Diarahkan ke login |
| 2 | Tambah Tang Crimping jumlah 2 | Masuk keranjang; menu atas "Keranjang (2)" |
| 3 | Tambah lagi 5 (stok hanya 3) | Jumlah dibatasi 3, muncul pesan batas stok |
| 4 | Tambah LAN Tester (stok 0) | Ditolak: "Stok produk habis." |
| 5 | Ubah jumlah menjadi 1 | Subtotal dan total ikut berubah |
| 6 | Ubah ke 0 atau klik Hapus | Item hilang; keranjang kosong menampilkan pesan |
| 7 | Login sebagai admin lalu buka `/keranjang` | Ditolak, diarahkan ke beranda |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `08-keranjang`
- [ ] Tidak ada file di luar `fitur/08-keranjang/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-06` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
