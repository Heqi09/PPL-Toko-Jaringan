// FITUR 5 — KATALOG PRODUK (kartu PPL-03)
// Alamat   : GET / (beranda = daftar semua produk, boleh tanpa login)
// Tampilan : katalog.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel products + categories)
//
// Titik sambung dengan fitur 6 (cari-filter), TIDAK wajib ada:
//   - res.locals.filter     = { where: [potongan SQL], params: [nilai] }  -> menyaring query
//   - res.locals.filterForm = HTML form pencarian                          -> ditampilkan di atas daftar
// Kalau fitur 6 belum ada, dua nilai itu kosong dan katalog menampilkan semua produk.
const express = require('express');
const db = require('../../db');

const router = express.Router();

router.get('/', (req, res) => {
  const f = res.locals.filter || { where: [], params: [] };
  const sql = `SELECT p.*, c.name AS category_name
               FROM products p JOIN categories c ON c.id = p.category_id
               ${f.where.length ? 'WHERE ' + f.where.join(' AND ') : ''}
               ORDER BY p.created_at DESC, p.id DESC`;
  const products = db.prepare(sql).all(...f.params);
  res.render('katalog', { title: 'Katalog', products });
});

module.exports = router;
