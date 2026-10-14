# Fitur 4 — CRUD Produk Admin

**Kartu:** PPL-02 · **Ukuran:** Besar · **Pemegang:** (isi nama)

Admin melihat, menambah, mengubah, dan menghapus produk (nama, merek, kategori, harga, stok, **berat gram**, garansi, deskripsi, spesifikasi) serta mengunggah foto. Foto disimpan di `public/assets/gambar/` dan alamatnya di database berbentuk `assets/gambar/rj45.jpg`. Semua alamat `/admin/...` hanya untuk admin.

## Isi folder `fitur/04-crud-produk/`

| File | Fungsi |
|---|---|
| `index.js` | Route CRUD, validasi form, upload foto (library multer), hapus file foto lama |
| `admin-produk-list.ejs` | Tampilan tabel daftar produk |
| `admin-produk-form.ejs` | Tampilan form tambah dan ubah produk (ada input foto) |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /admin/produk`
- `GET /admin/produk/baru`
- `POST /admin/produk`
- `GET /admin/produk/:id/edit`
- `POST /admin/produk/:id`
- `POST /admin/produk/:id/hapus`

## Bergantung pada

Fondasi (termasuk folder `public/assets/gambar/`). Untuk masuk sebagai admin dibutuhkan fitur 2 (login). Hasilnya terlihat di fitur 5 (katalog).

## Cara mencoba

- Admin: login, buka menu **Kelola Produk**, klik **+ Tambah Produk**, isi form, pilih foto (JPG, PNG, atau WEBP maksimal 2 MB), **Simpan**.
- Customer: tidak punya akses. Membuka `/admin/produk` mendapat "Akses ditolak".

## Uji sebelum Pull Request

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

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `04-crud-produk`
- [ ] Tidak ada file di luar `fitur/04-crud-produk/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-02` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
