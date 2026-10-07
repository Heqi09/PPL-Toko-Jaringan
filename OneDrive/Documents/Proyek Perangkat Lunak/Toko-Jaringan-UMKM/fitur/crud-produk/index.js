// FITUR 4 — CRUD PRODUK ADMIN (kartu PPL-02)
// Alamat   : /admin/produk (daftar), /admin/produk/baru, /admin/produk/:id/edit,
//            POST /admin/produk, POST /admin/produk/:id, POST /admin/produk/:id/hapus
// Tampilan : admin-produk-list.ejs dan admin-produk-form.ejs (di folder ini)
// Foto     : diunggah lewat form, disimpan di public/assets/gambar/, alamatnya di database
//            berbentuk 'assets/gambar/rj45.jpg'
// Memakai  : fondasi (db.js, middleware/auth.js). Semua alamat /admin/... khusus admin.
const express = require('express');
const multer = require('multer');
const path = require('path');
const fs = require('fs');
const db = require('../../db');
const { requireAdmin } = require('../../middleware/auth');

const router = express.Router();
router.use('/admin', requireAdmin); // semua alamat /admin/... hanya untuk admin

// ---- Upload foto produk: disimpan ke public/assets/gambar/ ----
const UPLOAD_DIR = path.join(__dirname, '..', '..', 'public', 'assets', 'gambar');
fs.mkdirSync(UPLOAD_DIR, { recursive: true });

const ALLOWED = { 'image/jpeg': '.jpg', 'image/png': '.png', 'image/webp': '.webp' };

const upload = multer({
  storage: multer.diskStorage({
    destination: (req, file, cb) => cb(null, UPLOAD_DIR),
    filename: (req, file, cb) => {
      const ext = ALLOWED[file.mimetype];
      const base =
        path.parse(file.originalname).name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '') || 'produk';
      let name = base + ext; // contoh: rj45.jpg
      if (fs.existsSync(path.join(UPLOAD_DIR, name))) name = `${base}-${Date.now()}${ext}`; // jangan menimpa
      cb(null, name);
    },
  }),
  limits: { fileSize: 2 * 1024 * 1024 }, // maks. 2 MB
  fileFilter: (req, file, cb) => (ALLOWED[file.mimetype] ? cb(null, true) : cb(new Error('FORMAT'))),
});

// Middleware: baca field "foto"; kesalahan upload dicatat di req.uploadError
function uploadFoto(req, res, next) {
  upload.single('foto')(req, res, (err) => {
    if (err) {
      req.uploadError =
        err.code === 'LIMIT_FILE_SIZE' ? 'Ukuran foto maksimal 2 MB.'
        : err.message === 'FORMAT' ? 'Format foto harus JPG, PNG, atau WEBP.'
        : 'Upload foto gagal.';
    }
    next();
  });
}

const removeUploaded = (file) => file && fs.unlink(file.path, () => {}); // batalkan file yang terlanjur tersimpan
function removeImage(imageUrl) {
  if (imageUrl && imageUrl.startsWith('assets/gambar/')) {
    fs.unlink(path.join(UPLOAD_DIR, path.basename(imageUrl)), () => {}); // hapus file lama
  }
}

function parseForm(body) {
  const f = {
    name: (body.name || '').trim(),
    brand: (body.brand || '').trim(),
    category_id: parseInt(body.category_id, 10),
    description: (body.description || '').trim(),
    specs: (body.specs || '').trim(),
    warranty: (body.warranty || '').trim(),
    price: parseInt(body.price, 10),
    stock: parseInt(body.stock, 10),
    weight_gram: parseInt(body.weight_gram, 10),
  };
  const errors = [];
  if (f.name.length < 2) errors.push('Nama produk minimal 2 karakter.');
  if (!db.prepare('SELECT id FROM categories WHERE id=?').get(f.category_id)) errors.push('Kategori tidak valid.');
  if (!Number.isInteger(f.price) || f.price < 0) errors.push('Harga harus angka 0 atau lebih.');
  if (!Number.isInteger(f.stock) || f.stock < 0) errors.push('Stok harus angka 0 atau lebih.');
  if (!Number.isInteger(f.weight_gram) || f.weight_gram <= 0) errors.push('Berat (gram) harus lebih dari 0.');
  return { f, errors };
}

const categories = () => db.prepare('SELECT * FROM categories ORDER BY name').all();

router.get('/admin/produk', (req, res) => {
  const products = db
    .prepare(
      `SELECT p.*, c.name AS category_name FROM products p
       JOIN categories c ON c.id = p.category_id ORDER BY p.id DESC`
    )
    .all();
  res.render('admin-produk-list', { title: 'Kelola Produk', products });
});

router.get('/admin/produk/baru', (req, res) => {
  res.render('admin-produk-form', { title: 'Tambah Produk', product: {}, categories: categories(), errors: [], action: '/admin/produk' });
});

router.post('/admin/produk', uploadFoto, (req, res) => {
  const { f, errors } = parseForm(req.body);
  if (req.uploadError) errors.push(req.uploadError);
  if (errors.length) {
    removeUploaded(req.file);
    return res.status(400).render('admin-produk-form', { title: 'Tambah Produk', product: f, categories: categories(), errors, action: '/admin/produk' });
  }
  f.image_url = req.file ? 'assets/gambar/' + req.file.filename : '';
  db.prepare(
    `INSERT INTO products (category_id,name,brand,description,specs,warranty,price,stock,weight_gram,image_url)
     VALUES (@category_id,@name,@brand,@description,@specs,@warranty,@price,@stock,@weight_gram,@image_url)`
  ).run(f);
  req.session.flash = { type: 'success', text: 'Produk berhasil ditambahkan.' };
  res.redirect('/admin/produk');
});

router.get('/admin/produk/:id/edit', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const product = db.prepare('SELECT * FROM products WHERE id=?').get(id);
  if (!product) {
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/admin/produk');
  }
  res.render('admin-produk-form', { title: 'Ubah Produk', product, categories: categories(), errors: [], action: `/admin/produk/${id}` });
});

router.post('/admin/produk/:id', uploadFoto, (req, res) => {
  const id = parseInt(req.params.id, 10);
  const old = db.prepare('SELECT id, image_url FROM products WHERE id=?').get(id);
  if (!old) {
    removeUploaded(req.file);
    req.session.flash = { type: 'error', text: 'Produk tidak ditemukan.' };
    return res.redirect('/admin/produk');
  }
  const { f, errors } = parseForm(req.body);
  if (req.uploadError) errors.push(req.uploadError);
  if (errors.length) {
    removeUploaded(req.file);
    return res.status(400).render('admin-produk-form', { title: 'Ubah Produk', product: { ...f, id, image_url: old.image_url }, categories: categories(), errors, action: `/admin/produk/${id}` });
  }
  f.image_url = req.file ? 'assets/gambar/' + req.file.filename : old.image_url; // tanpa file baru = foto lama dipertahankan
  db.prepare(
    `UPDATE products SET category_id=@category_id, name=@name, brand=@brand, description=@description,
       specs=@specs, warranty=@warranty, price=@price, stock=@stock, weight_gram=@weight_gram, image_url=@image_url
     WHERE id=@id`
  ).run({ ...f, id });
  if (req.file) removeImage(old.image_url); // foto diganti: hapus file lama
  req.session.flash = { type: 'success', text: 'Produk berhasil diperbarui.' };
  res.redirect('/admin/produk');
});

router.post('/admin/produk/:id/hapus', (req, res) => {
  const id = parseInt(req.params.id, 10);
  const old = db.prepare('SELECT image_url FROM products WHERE id=?').get(id);
  db.prepare('DELETE FROM products WHERE id=?').run(id);
  if (old) removeImage(old.image_url); // hapus file foto bersama produknya
  req.session.flash = { type: 'success', text: 'Produk dihapus.' };
  res.redirect('/admin/produk');
});

module.exports = router;