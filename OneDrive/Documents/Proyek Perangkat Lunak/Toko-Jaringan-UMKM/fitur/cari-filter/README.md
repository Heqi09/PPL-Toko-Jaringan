# Fitur 6 — Cari dan Filter Kategori

**Kartu:** PPL-04 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Kotak pencarian (nama atau merek) dan dropdown kategori di atas katalog. Keduanya bisa digabung, ada tombol Reset. Fitur ini tidak punya halaman sendiri: ia menyisipkan pencarian ke katalog lewat `middleware.js`.

## Isi folder `fitur/06-cari-filter/`

| File | Fungsi |
|---|---|
| `middleware.js` | Membaca `?q=` dan `?kategori=`, menyiapkan filter SQL dan HTML form untuk katalog |
| `filter-form.ejs` | Potongan tampilan form pencarian |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /?q=kata&kategori=ID (memperluas halaman katalog)`

## Bergantung pada

Fitur 5 (katalog), karena yang ditampilkan dan disaring adalah daftar katalog.

## Cara mencoba

- Customer atau tamu: di beranda ketik kata kunci dan/atau pilih kategori, klik **Cari**; **Reset** untuk membersihkan.

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Pilih kategori Router lalu Cari | Hanya 2 router yang tampil |
| 2 | Cari "crimping" | Hanya Tang Crimping |
| 3 | Cari "TP-Link" dengan kategori Switch | Hanya switch merek TP-Link |
| 4 | Cari "zzzz" | Pesan "Tidak ada produk yang cocok." |
| 5 | Klik Reset | Semua produk kembali |
| 6 | Ketik `<script>alert(1)</script>` di kotak cari | Tidak dijalankan, hanya tampil sebagai teks |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `06-cari-filter`
- [ ] Tidak ada file di luar `fitur/06-cari-filter/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-04` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
