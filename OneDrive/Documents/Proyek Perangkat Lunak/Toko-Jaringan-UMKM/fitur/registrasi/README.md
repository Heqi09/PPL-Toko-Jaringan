# Fitur 1 — Registrasi

**Kartu:** PPL-01 · **Ukuran:** Sedang · **Pemegang:** (isi nama)

Calon pembeli membuat akun: nama, email, password (minimal 8 karakter), dan konfirmasi password. Password disimpan sebagai hash (bcrypt). Akun yang dibuat dari halaman ini selalu berperan pembeli.

## Isi folder `fitur/01-registrasi/`

| File | Fungsi |
|---|---|
| `index.js` | Route GET dan POST `/register`, validasi input, simpan akun |
| `registrasi.ejs` | Tampilan form pendaftaran |
| `README.md` | Catatan ini |

## Alamat (route)

- `GET /register`
- `POST /register`

## Bergantung pada

Fondasi saja. Setelah berhasil daftar, pengguna diarahkan ke `/login` (fitur 2). Bila fitur 2 belum digabung, halaman itu masih 404 dan itu wajar.

## Cara mencoba

- Customer: buka `http://localhost:3000/register`, isi form, klik **Daftar**.
- Admin: tidak lewat halaman ini. Akun admin dibuat otomatis dari `.env`.

## Uji sebelum Pull Request

| No | Langkah | Hasil yang diharapkan |
|---|---|---|
| 1 | Isi email `bukan-email` | Ditolak: "Format email tidak valid." |
| 2 | Isi password hanya 3 karakter | Ditolak: "Password minimal 8 karakter." |
| 3 | Isi konfirmasi password yang berbeda | Ditolak: "Konfirmasi password tidak sama." |
| 4 | Isi semua data dengan benar | Diarahkan ke `/login` dengan pesan sukses |
| 5 | Daftar lagi dengan email yang sama | Ditolak: "Email sudah terdaftar." |
| 6 | Buka `data.sqlite` dengan DB Browser for SQLite, tabel `users` | `password_hash` berisi teks acak berawalan `$2`, bukan password asli; `role` = buyer |

## Git

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

## Checklist Done

- [ ] Semua baris tabel Uji di atas lulus
- [ ] `npm start` tanpa error dan daftar "Fitur dimuat" memuat folder `01-registrasi`
- [ ] Tidak ada file di luar `fitur/01-registrasi/` yang diubah (perubahan fondasi lewat kartu PPL-24)
- [ ] Kode `PPL-01` ada di branch, commit, dan judul PR
- [ ] PR sudah di-review minimal 1 anggota lain sebelum merge
- [ ] Tidak ada password atau API key di file
