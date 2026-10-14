# Fitur 2 — Login

**Kartu:** PPL-01 · **Ukuran:** Sedang · **Pemegang:** (isi nama)

Pembeli dan admin masuk dengan email dan password. Admin diarahkan ke Kelola Produk, pembeli ke beranda. Sesi dibuat ulang saat login.

## Isi folder `fitur/02-login/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET dan POST `/login`, cek password terhadap hash |
| `login.ejs` | Tampilan form login |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /login`
- `POST /login`

## Bergantung pada

Fondasi saja. Login admin bisa dicoba tanpa fitur 1 (akun admin dari `.env`). Login pembeli butuh akun dari fitur 1.

## Cara mencoba

- Customer: buka `http://localhost:3000/login`, masuk dengan akun yang didaftarkan.
- Admin: masuk dengan `ADMIN_EMAIL` dan `ADMIN_PASSWORD` dari `.env`; diarahkan ke `/admin/produk` (404 bila fitur 4 belum digabung, wajar).

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Login admin dengan data dari `.env` | Masuk, diarahkan ke `/admin/produk` |
| 2 | Login dengan password salah | Ditolak: "Email atau password salah." |
| 3 | Login dengan akun pembeli (dari fitur 1) | Masuk dan diarahkan ke beranda |
| 4 | Setelah login, buka `/login` lagi | Diarahkan ke beranda |
| 5 | Lihat menu atas | Menampilkan nama akun; menu Masuk dan Daftar hilang |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `02-login`
- [ ] Tidak ada file di luar `fitur/02-login/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
