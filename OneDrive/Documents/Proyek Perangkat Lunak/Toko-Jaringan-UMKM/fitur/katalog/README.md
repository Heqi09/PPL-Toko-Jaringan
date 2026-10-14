# Fitur 5 — Katalog Produk

**Kartu:** PPL-03 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Halaman beranda menampilkan semua produk dalam bentuk kartu: foto (atau kotak "Tanpa foto"), nama, harga, kategori, merek, dan stok. Produk dengan stok 0 berlabel **Stok habis**. Boleh dilihat tanpa login.

## Isi folder `fitur/05-katalog/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET `/`, mengambil produk dari database |
| `katalog.ejs` | Tampilan grid kartu produk |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /`

## Bergantung pada

Fondasi saja. Titik sambung opsional dengan fitur 6: bila ada, katalog otomatis memakai pencarian dan filternya (dijelaskan di komentar `index.js`).

## Cara mencoba

- Customer atau tamu: buka `http://localhost:3000/`.
- Admin: produk baru dari fitur 4 langsung terlihat di sini.

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Buka `/` tanpa login | Semua produk tampil dengan nama, harga, kategori, merek |
| 2 | Lihat "LAN Tester RJ45" | Berlabel "Stok habis" |
| 3 | Perkecil jendela browser ke lebar HP | Kartu tersusun rapi |
| 4 | Lihat produk tanpa foto | Tampil kotak "Tanpa foto" |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `05-katalog`
- [ ] Tidak ada file di luar `fitur/05-katalog/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-03` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
