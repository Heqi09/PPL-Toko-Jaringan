# Fitur 7 — Detail Produk

**Kartu:** PPL-05 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Halaman satu produk: deskripsi, spesifikasi (satu baris menjadi satu poin), merek, garansi, berat, stok, harga, dan tombol **Tambah ke keranjang**. Tamu melihat "Masuk untuk membeli"; stok 0 menampilkan label Stok habis.

## Isi folder `fitur/07-detail-produk/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET `/produk/:id` |
| `detail-produk.ejs` | Tampilan halaman detail |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /produk/:id`

## Bergantung pada

Fondasi saja (bisa dibuka lewat alamat langsung). Tautan menuju halaman ini ada di fitur 5. Tombol beli baru berfungsi setelah fitur 8.

## Cara mencoba

- Customer atau tamu: klik nama produk di katalog, atau buka `http://localhost:3000/produk/1`.

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/produk/1` sebagai tamu | Detail lengkap; tombol "Masuk untuk membeli" |
| 2 | Buka `/produk/1` sebagai pembeli | Form jumlah dan tombol "Tambah ke keranjang" |
| 3 | Buka `/produk/10` (stok 0) | Label "Stok habis", tanpa tombol beli |
| 4 | Buka `/produk/9999` | Halaman "Produk tidak ditemukan" (404) |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `07-detail-produk`
- [ ] Tidak ada file di luar `fitur/07-detail-produk/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-05` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
