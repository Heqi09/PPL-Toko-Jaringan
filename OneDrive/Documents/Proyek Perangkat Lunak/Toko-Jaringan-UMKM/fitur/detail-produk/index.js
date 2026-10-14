// FITUR 7 — DETAIL PRODUK (kartu PPL-05)
// Alamat   : GET /produk/:id
// Tampilan : detail-produk.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel products + categories)
// Catatan  : tombol "Tambah ke keranjang" di halaman ini mengirim ke fitur 8 (keranjang);
//            tombolnya baru berfungsi setelah fitur 8 digabung.
const express = require('express');
const db = require('../../db');

const router = express.Router();

router.get('/produk/:id', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const product = db
    .prepare(
      `SELECT p.*, c.name AS category_name
       FROM products p JOIN categories c ON c.id = p.category_id WHERE p.id = ?`
    )
    .get(id);
  if (!product) {
    return res.status(404).render('error', { title: 'Tidak ditemukan', message: 'Produk tidak ditemukan.' });
  }
  res.render('detail-produk', { title: product.name, product });
});

module.exports = router;
