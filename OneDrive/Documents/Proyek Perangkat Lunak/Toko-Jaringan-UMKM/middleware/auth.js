// Middleware otorisasi — PPL-01 (login) & PPL-02 (hanya admin)
function requireLogin(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login terlebih dahulu.' };
    return res.redirect('/login');
  }
  next();
}

function requireBuyer(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login untuk memakai keranjang.' };
    return res.redirect('/login');
  }
  if (req.session.user.role !== 'buyer') {
    req.session.flash = { type: 'error', text: 'Keranjang hanya untuk akun pembeli.' };
    return res.redirect('/');
  }
  next();
}

function requireAdmin(req, res, next) {
  if (!req.session.user) {
    req.session.flash = { type: 'error', text: 'Silakan login terlebih dahulu.' };
    return res.redirect('/login');
  }
  if (req.session.user.role !== 'admin') {
    return res.status(403).render('error', { title: 'Akses ditolak', message: 'Halaman ini hanya untuk admin.' });
  }
  next();
}

module.exports = { requireLogin, requireBuyer, requireAdmin };
