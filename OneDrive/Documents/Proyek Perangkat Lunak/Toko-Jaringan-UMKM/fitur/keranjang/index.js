// FITUR 8 — KERANJANG BELANJA (kartu PPL-06)
// Alamat   : GET /keranjang, POST /keranjang/tambah, /keranjang/ubah, /keranjang/hapus
// Tampilan : keranjang.ejs (di folder ini)
// Memakai  : fondasi (db.js tabel cart_items, middleware/auth.js). Hanya untuk akun pembeli.
// Catatan  : tombol "Tambah ke keranjang" ada di halaman detail produk (fitur 7).
//            Angka keranjang di menu atas dihitung di server.js (fondasi).
const express = require('express');
const db = require('../../db');
const { requireBuyer } = require('../../middleware/auth');

const router = express.Router();
router.use('/keranjang', requireBuyer); // semua alamat /keranjang... hanya untuk pembeli

router.get('/keranjang', (req, res) => {
  const items = db
    .prepare(
      `SELECT ci.id, ci.qty, p.id AS product_id, p.name, p.brand, p.price, p.stock, p.weight_gram, p.image_url
       FROM cart_items ci JOIN products p ON p.id = ci.product_id
       WHERE ci.user_id = ? ORDER BY ci.id`
    )
    .all(req.session.user.id);
  const total = items.reduce((s, i) => s + i.price * i.qty, 0);
  const totalWeight = items.reduce((s, i) => s + i.weight_gram * i.qty, 0);
  res.render('keranjang', { title: 'Keranjang', items, total, totalWeight });
});

router.post('/keranjang/tambah', (req, res) => {
  const userId = req.session.user.id;
  const productId = parseInt(req.body.product_id, 10);
  const qty = Math.max(1, parseInt(req.body.qty, 10) || 1);
  const back = req.get('Referer') || '/';

  const product = db.prepare('SELECT * FROM products WHERE id=?').get(productId);
  if (!product) {
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/');
  }
  if (product.stock < 1) {
    req.session.flash = { type: 'error', text: 'Stok produk habis.' };
    return res.redirect(back);
  }

  const existing = db
    .prepare('SELECT qty FROM cart_items WHERE user_id=? AND product_id=?')
    .get(userId, productId);
  const wanted = (existing ? existing.qty : 0) + qty;
  const finalQty = Math.min(wanted, product.stock);

  db.prepare(
    `INSERT INTO cart_items (user_id, product_id, qty) VALUES (?,?,?)
     ON CONFLICT(user_id, product_id) DO UPDATE SET qty = excluded.qty`
  ).run(userId, productId, finalQty);

  req.session.flash =
    finalQty < wanted
      ? { type: 'error', text: `Jumlah dibatasi sesuai stok (${product.stock}).` }
      : { type: 'success', text: 'Produk ditambahkan ke keranjang.' };
  res.redirect(back);
});

router.post('/keranjang/ubah', (req, res) => {
  const userId = req.session.user.id;
  const productId = parseInt(req.body.product_id, 10);
  const qty = parseInt(req.body.qty, 10);
  const product = db.prepare('SELECT stock FROM products WHERE id=?').get(productId);

  if (!product || Number.isNaN(qty)) {
    req.session.flash = { type: 'error', text: 'Data tidak valid.' };
    return res.redirect('/keranjang');
  }
  if (qty <= 0) {
    db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(userId, productId);
    req.session.flash = { type: 'success', text: 'Item dihapus dari keranjang.' };
    return res.redirect('/keranjang');
  }
  const finalQty = Math.min(qty, product.stock);
  if (finalQty < 1) {
    db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(userId, productId);
  } else {
    db.prepare('UPDATE cart_items SET qty=? WHERE user_id=? AND product_id=?').run(finalQty, userId, productId);
  }
  req.session.flash =
    finalQty < qty
      ? { type: 'error', text: `Jumlah dibatasi sesuai stok (${product.stock}).` }
      : { type: 'success', text: 'Keranjang diperbarui.' };
  res.redirect('/keranjang');
});

router.post('/keranjang/hapus', (req, res) => {
  const productId = parseInt(req.body.product_id, 10);
  db.prepare('DELETE FROM cart_items WHERE user_id=? AND product_id=?').run(req.session.user.id, productId);
  req.session.flash = { type: 'success', text: 'Item dihapus dari keranjang.' };
  res.redirect('/keranjang');
});

module.exports = router;
