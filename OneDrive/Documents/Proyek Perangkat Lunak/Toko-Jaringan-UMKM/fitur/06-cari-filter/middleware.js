// FITUR 6 — CARI DAN FILTER KATEGORI (kartu PPL-04)
// Fitur ini TIDAK punya halaman sendiri. Ia "menyisipkan" pencarian ke katalog (fitur 5)
// lewat dua nilai di res.locals, lalu katalog memakainya:
//   res.locals.filter     = { where, params }  potongan SQL untuk menyaring produk
//   res.locals.filterForm = HTML kotak cari + dropdown kategori (dari filter-form.ejs)
// File ini dimuat lebih dulu dari semua index.js (lihat muat-fitur.js).
// Alamat   : GET /?q=kata&kategori=ID
// Memakai  : fondasi saja (db.js tabel categories)
const express = require('express');
const fs = require('fs');
const path = require('path');
const ejs = require('ejs');
const db = require('../../db');

const router = express.Router();
const formTemplate = fs.readFileSync(path.join(__dirname, 'filter-form.ejs'), 'utf8');

router.get('/', (req, res, next) => {
  const q = (req.query.q || '').trim();
  const kategori = parseInt(req.query.kategori, 10) || null;

  // Nilai pencarian dikirim sebagai parameter (tanda ?), bukan disambung ke teks SQL,
  // sehingga aman dari SQL injection.
  const where = [];
  const params = [];
  if (q) {
    where.push('(p.name LIKE ? OR p.brand LIKE ?)');
    params.push(`%${q}%`, `%${q}%`);
  }
  if (kategori) {
    where.push('p.category_id = ?');
    params.push(kategori);
  }
  res.locals.filter = { where, params };

  const categories = db.prepare('SELECT * FROM categories ORDER BY name').all();
  res.locals.filterForm = ejs.render(formTemplate, { q, kategori, categories });
  next(); // lanjut ke katalog (fitur 5)
});

module.exports = router;
