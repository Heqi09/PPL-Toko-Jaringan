// Pemuat fitur otomatis.
//
// Setiap folder di fitur/ adalah SATU fitur (satu kartu). Server memuat semuanya
// secara otomatis, jadi menambah fitur = cukup menaruh foldernya. Tidak perlu
// mengubah server.js, sehingga anggota tim tidak saling bentrok di GitHub.
//
// Isi folder sebuah fitur:
//   index.js       route fitur (wajib kecuali fitur hanya berupa middleware)
//   middleware.js  opsional. Dimuat LEBIH DULU dari semua index.js, dipakai fitur
//                  yang "menyisipkan" sesuatu ke fitur lain (contoh: cari-filter)
//   *.ejs          tampilan milik fitur itu (nama file harus unik antar fitur)
//
// Urutan muat = urutan nama folder (01-, 02-, ...).
const fs = require('fs');
const path = require('path');

const FITUR_DIR = path.join(__dirname, 'fitur');

function namaFitur() {
  if (!fs.existsSync(FITUR_DIR)) return [];
  return fs
    .readdirSync(FITUR_DIR, { withFileTypes: true })
    .filter((d) => d.isDirectory())
    .map((d) => d.name)
    .sort();
}

// Folder tampilan: views/ (fondasi) + folder setiap fitur
function viewDirs() {
  return [path.join(__dirname, 'views'), ...namaFitur().map((n) => path.join(FITUR_DIR, n))];
}

function muatFitur(app) {
  const names = namaFitur();
  const dimuat = [];

  for (const n of names) {
    const f = path.join(FITUR_DIR, n, 'middleware.js');
    if (fs.existsSync(f)) {
      app.use(require(f));
      dimuat.push(`${n}/middleware.js`);
    }
  }
  for (const n of names) {
    const f = path.join(FITUR_DIR, n, 'index.js');
    if (fs.existsSync(f)) {
      app.use(require(f));
      dimuat.push(`${n}/index.js`);
    }
  }
  console.log(dimuat.length ? 'Fitur dimuat:\n  ' + dimuat.join('\n  ') : 'Belum ada fitur di folder fitur/');
}

module.exports = { muatFitur, viewDirs };
