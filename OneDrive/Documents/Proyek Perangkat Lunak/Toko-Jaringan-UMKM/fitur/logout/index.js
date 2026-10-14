// FITUR 3 — LOGOUT (kartu PPL-01)
// Alamat   : POST /logout (tombol "Keluar" ada di menu atas, views/partials/header.ejs)
// Tampilan : tidak ada (langsung kembali ke beranda)
// Memakai  : fondasi saja (session dari server.js)
const express = require('express');

const router = express.Router();

router.post('/logout', (req, res) => {
  req.session.destroy(() => res.redirect('/'));
});

module.exports = router;
