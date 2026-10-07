// FITUR 1 — REGISTRASI PEMBELI (kartu PPL-01)
// Alamat   : GET /register (form), POST /register (simpan akun pembeli baru)
// Tampilan : registrasi.ejs (di folder ini)
// Memakai  : fondasi saja (db.js tabel users)
const express = require('express');
const bcrypt = require('bcryptjs');
const db = require('../../db');

const router = express.Router();
const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

router.get('/register', (req, res) => {
  if (req.session.user) return res.redirect('/');
  res.render('registrasi', { title: 'Daftar', form: {}, errors: [] });
});

router.post('/register', (req, res) => {
  const name = (req.body.name || '').trim();
  const email = (req.body.email || '').trim().toLowerCase();
  const password = req.body.password || '';
  const confirm = req.body.confirm || '';
  const errors = [];

  if (name.length < 2) errors.push('Nama minimal 2 karakter.');
  if (!EMAIL_RE.test(email)) errors.push('Format email tidak valid.');
  if (password.length < 8) errors.push('Password minimal 8 karakter.');
  if (password !== confirm) errors.push('Konfirmasi password tidak sama.');
  if (!errors.length && db.prepare('SELECT id FROM users WHERE email=?').get(email)) {
    errors.push('Email sudah terdaftar.');
  }
  if (errors.length) {
    return res.status(400).render('registrasi', { title: 'Daftar', form: { name, email }, errors });
  }

  // Password disimpan sebagai hash, bukan teks asli. Akun baru selalu berperan 'buyer'.
  db.prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)').run(
    name,
    email,
    bcrypt.hashSync(password, 10),
    'buyer'
  );
  req.session.flash = { type: 'success', text: 'Registrasi berhasil. Silakan login.' };
  res.redirect('/login');
});

module.exports = router;
