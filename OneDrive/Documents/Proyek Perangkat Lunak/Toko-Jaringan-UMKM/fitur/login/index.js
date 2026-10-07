// FITUR 2 — LOGIN (kartu PPL-01)
// Alamat   : GET /login (form), POST /login (cek email + password)
// Tampilan : login.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel users)
// Catatan  : akun admin dibuat otomatis dari .env oleh db.js, jadi login admin
//            bisa dicoba tanpa menunggu fitur Registrasi.
const express = require('express');
const bcrypt = require('bcryptjs');
const db = require('../../db');

const router = express.Router();

router.get('/login', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('login', { title: 'Login', form: {}, error: null });
});

router.post('/login', (req, res) => {
  const email = (req.body.email || '').trim().toLowerCase();
  const password = req.body.password || '';
  const row = db.prepare('SELECT * FROM users WHERE email=?').get(email);

  if (!row || !bcrypt.compareSync(password, row.password_hash)) {
    return res.status(401).render('login', { title: 'Login', form: { email }, error: 'Email atau password salah.' });
  }
  const sessionUser = { id: row.id, name: row.name, email: row.email, role: row.role };
  req.session.regenerate((err) => {
    if (err) throw err;
    req.session.user = sessionUser;
    // admin langsung ke halaman kelola produk, pembeli ke beranda
    res.redirect(row.role === 'admin' ? '/admin/produk' : '/');
  });
});

module.exports = router;