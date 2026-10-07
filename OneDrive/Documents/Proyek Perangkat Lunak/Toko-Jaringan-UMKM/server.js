// Server utama — toko jaringan (Sprint 1)
require('dotenv').config();
const express = require('express');
const session = require('express-session');
const path = require('path');
const db = require('./db');
const { muatFitur, viewDirs } = require('./muat-fitur');

const app = express();
app.set('view engine', 'ejs');
app.set('views', viewDirs()); // views/ (fondasi) + folder tampilan tiap fitur di fitur/

app.use(express.urlencoded({ extended: false }));
app.use(express.static(path.join(__dirname, 'public')));
app.use(
  session({
    secret: process.env.SESSION_SECRET || 'dev-secret-ganti-di-env',
    resave: false,
    saveUninitialized: false,
    cookie: { httpOnly: true, sameSite: 'lax', maxAge: 1000 * 60 * 60 * 8 },
  })
);

// Data yang tersedia di semua view
app.use((req, res, next) => {
  const u = req.session.user || null;
  res.locals.user = u;
  res.locals.flash = req.session.flash || null;
  delete req.session.flash;
  res.locals.rupiah = (n) => 'Rp' + Number(n).toLocaleString('id-ID');
  // 'assets/gambar/rj45.jpg' -> '/assets/gambar/rj45.jpg' (URL http(s) lama tetap dipakai apa adanya)
  res.locals.imgSrc = (u) => (!u ? '' : /^https?:\/\//i.test(u) ? u : '/' + u.replace(/^\/+/, ''));
  res.locals.filterForm = ''; // diisi oleh fitur 6 (cari-filter) bila fitur itu ada
  res.locals.cartCount = 0;
  if (u && u.role === 'buyer') {
    res.locals.cartCount = db
      .prepare('SELECT COALESCE(SUM(qty),0) c FROM cart_items WHERE user_id=?')
      .get(u.id).c;
  }
  next();
});

// Memuat semua folder di fitur/ secara otomatis (lihat muat-fitur.js)
muatFitur(app);

app.use((req, res) => {
  res.status(404).render('error', { title: 'Tidak ditemukan', message: 'Halaman tidak ditemukan.' });
});

app.use((err, req, res, next) => {
  console.error(err);
  res.status(500).render('error', { title: 'Kesalahan', message: 'Terjadi kesalahan pada server.' });
});

const PORT = process.env.PORT || 3000;
if (require.main === module) {
  app.listen(PORT, () => console.log(`Toko Jaringan berjalan di http://localhost:${PORT}`));
}
module.exports = app;
