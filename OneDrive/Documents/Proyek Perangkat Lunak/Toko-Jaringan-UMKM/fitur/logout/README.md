# Fitur 3 — Logout

**Kartu:** PPL-01 · **Ukuran:** Kecil · **Pemegang:** (isi nama)

Menghapus sesi login lalu kembali ke beranda. Tombol **Keluar** sudah ada di menu atas (fondasi), fitur ini yang membuatnya berfungsi.

## Isi folder `fitur/03-logout/`

| File | Fungsi |
|---|---|
| `index.js` | Route POST `/logout` |
| `README.md` | Catatan ini |

## Alamat (route)

- `POST /logout`

## Bergantung pada

Fondasi saja, tetapi baru bisa dicoba setelah fitur 2 (login) ada.

## Cara mencoba

- Customer atau admin: setelah login, klik **Keluar** di menu atas.

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Login, lalu klik **Keluar** | Kembali ke beranda; menu Masuk dan Daftar muncul lagi |
| 2 | Setelah keluar, buka `/admin/produk` | Diarahkan ke `/login` (bila fitur 4 ada) |
| 3 | Setelah keluar, refresh halaman | Tetap terlihat belum login |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `03-logout`
- [ ] Tidak ada file di luar `fitur/03-logout/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
