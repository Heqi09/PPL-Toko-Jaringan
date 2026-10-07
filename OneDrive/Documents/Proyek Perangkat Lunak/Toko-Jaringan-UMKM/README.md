# Toko Jaringan

Aplikasi e-commerce UMKM penjual peralatan jaringan (router, switch, access point, kabel UTP, konektor, tang crimping). Proyek mata kuliah Proyek Perangkat Lunak (PPL), semester ganjil 2026/2027.

## Status

Sprint 1: registrasi/login, kelola produk admin, katalog, pencarian + filter kategori, detail produk, keranjang.
Sprint 2 (berikutnya): checkout dan API pembayaran Midtrans (sandbox).

## Teknologi

Node.js, Express, EJS, SQLite (better-sqlite3), bcryptjs, express-session, multer (upload foto).

## Cara Menjalankan

```bash
npm install
cp .env.example .env     # lalu isi SESSION_SECRET, ADMIN_EMAIL, ADMIN_PASSWORD
npm start                # buka http://localhost:3000
```

Akun admin dibuat otomatis saat pertama kali dijalankan dari nilai `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env`. Data contoh produk dan kategori juga dibuat otomatis. File database `data.sqlite` dibuat otomatis dan tidak di-commit.

**Keamanan:** file `.env` berisi rahasia dan sudah masuk `.gitignore`. Jangan pernah commit API key atau password.

## Struktur

```
server.js              aplikasi utama
muat-fitur.js          memuat semua folder di fitur/ secara otomatis
db.js                  skema database + data awal
middleware/auth.js     pengaman halaman (login, pembeli, admin)
views/                 header, footer, halaman error
public/                style.css dan foto produk (assets/gambar/)
fitur/                 satu folder = satu fitur = satu kartu
  01-registrasi/       PPL-01
  02-login/            PPL-01
  03-logout/           PPL-01
  04-crud-produk/      PPL-02 (termasuk upload foto)
  05-katalog/          PPL-03
  06-cari-filter/      PPL-04
  07-detail-produk/    PPL-05
  08-keranjang/        PPL-06
docs/                  rincian kartu dan panduan pembagian tugas
```

Tiap folder fitur berisi route (`index.js`), tampilan (`.ejs`), dan `README.md` yang menjelaskan fitur, cara uji, dan perintah git.

## Alur Kerja Tim

- Board Agile + Slack/Discord + GitHub saling terhubung.
- Kode tugas yang sama (PPL-xx) di kartu, branch, commit, dan judul PR.
- Branch `feature/PPL-xx-nama`, commit `[PPL-xx] pesan`, merge ke `main` lewat PR yang di-review minimal satu anggota lain.
- Rincian kartu Sprint 1: lihat `docs/KARTU-SPRINT-1.md`.
- Kode per fitur dan cara menjalankan sebagai customer/admin: lihat `docs/PANDUAN-PER-FITUR.md`.

## Anggota dan Peran

| Nama | Peran |
|---|---|
| (isi) | Product Owner / System Analyst |
| (isi) | Scrum Master / Project Manager |
| (isi) | UI/UX Designer |
| (isi) | Backend Developer |
| (isi) | Frontend Developer |
| (isi) | QA / Tester |
| (isi) | Dokumentasi & Branding |

## Tautan

- Board: (isi)
- Workspace komunikasi: (isi)
- Catatan sprint: (isi)
