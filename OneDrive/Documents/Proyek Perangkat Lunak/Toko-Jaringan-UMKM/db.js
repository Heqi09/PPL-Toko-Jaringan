// Koneksi database SQLite + skema + data awal (seed)
// Terkait: PPL-01 (users), PPL-02 (products, categories), PPL-06 (cart_items)
const Database = require('better-sqlite3');
const bcrypt = require('bcryptjs');
const path = require('path');

const db = new Database(process.env.DB_FILE || path.join(__dirname, 'data.sqlite'));
db.pragma('journal_mode = WAL');
db.pragma('foreign_keys = ON');

db.exec(`
CREATE TABLE IF NOT EXISTS users (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL,
  email TEXT NOT NULL UNIQUE,
  password_hash TEXT NOT NULL,
  role TEXT NOT NULL DEFAULT 'buyer' CHECK (role IN ('buyer','admin')),
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  name TEXT NOT NULL UNIQUE
);

CREATE TABLE IF NOT EXISTS products (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  category_id INTEGER NOT NULL REFERENCES categories(id),
  name TEXT NOT NULL,
  brand TEXT,
  description TEXT,
  specs TEXT,
  warranty TEXT,
  price INTEGER NOT NULL CHECK (price >= 0),
  stock INTEGER NOT NULL DEFAULT 0 CHECK (stock >= 0),
  weight_gram INTEGER NOT NULL CHECK (weight_gram > 0),
  image_url TEXT,
  created_at TEXT DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS cart_items (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
  product_id INTEGER NOT NULL REFERENCES products(id) ON DELETE CASCADE,
  qty INTEGER NOT NULL CHECK (qty > 0),
  UNIQUE (user_id, product_id)
);
`);

function seed() {
  const catCount = db.prepare('SELECT COUNT(*) c FROM categories').get().c;
  if (catCount === 0) {
    const ins = db.prepare('INSERT INTO categories (name) VALUES (?)');
    ['Router', 'Switch', 'Access Point', 'Kabel', 'Aksesoris'].forEach((n) => ins.run(n));
  }

  const adminEmail = process.env.ADMIN_EMAIL || 'admin@toko.test';
  const adminPass = process.env.ADMIN_PASSWORD || 'admin12345';
  const hasAdmin = db.prepare("SELECT id FROM users WHERE role='admin' LIMIT 1").get();
  if (!hasAdmin) {
    db.prepare('INSERT INTO users (name, email, password_hash, role) VALUES (?,?,?,?)')
      .run('Admin Toko', adminEmail, bcrypt.hashSync(adminPass, 10), 'admin');
  }

  const prodCount = db.prepare('SELECT COUNT(*) c FROM products').get().c;
  if (prodCount === 0) {
    const cat = (n) => db.prepare('SELECT id FROM categories WHERE name=?').get(n).id;
    const ins = db.prepare(`INSERT INTO products
      (category_id,name,brand,description,specs,warranty,price,stock,weight_gram,image_url)
      VALUES (?,?,?,?,?,?,?,?,?,?)`);
    const rows = [
      [cat('Router'), 'Router WiFi AC1200 Dual Band', 'TP-Link', 'Router dual band untuk rumah dan kantor kecil.', '867 Mbps (5GHz) + 300 Mbps (2.4GHz)\n4 antena eksternal\n4 port LAN 100 Mbps', '1 tahun', 350000, 25, 450, ''],
      [cat('Router'), 'Router Gigabit 5 Port', 'Mikrotik', 'Router fleksibel dengan RouterOS.', '5 port Gigabit Ethernet\nCPU 650 MHz\nRouterOS L4', '1 tahun', 780000, 12, 300, ''],
      [cat('Switch'), 'Switch Gigabit 8 Port', 'TP-Link', 'Switch unmanaged plug and play.', '8 port 10/100/1000 Mbps\nCasing metal\nTanpa konfigurasi', '2 tahun', 280000, 30, 500, ''],
      [cat('Switch'), 'Switch PoE 8 Port', 'Ruijie', 'Switch dengan PoE untuk CCTV dan AP.', '8 port PoE 100 Mbps\nTotal PoE 96 W', '2 tahun', 620000, 4, 700, ''],
      [cat('Access Point'), 'Access Point Ceiling AC1200', 'Ubiquiti', 'AP langit-langit untuk area luas.', 'Dual band AC1200\nPoE 802.3af\nHingga 100 klien', '1 tahun', 1150000, 8, 350, ''],
      [cat('Kabel'), 'Kabel UTP Cat6 per Roll 305m', 'Belden', 'Kabel UTP Cat6 untuk instalasi LAN.', 'Cat6 solid copper\nPanjang 305 meter\nAWG 23', '-', 1450000, 10, 9500, ''],
      [cat('Kabel'), 'Patch Cord Cat6 2 Meter', 'Ugreen', 'Kabel patch siap pakai.', 'Cat6 UTP\nPanjang 2 meter\nKonektor RJ45', '-', 25000, 100, 80, ''],
      [cat('Aksesoris'), 'Tang Crimping RJ45/RJ11', 'Krisbow', 'Tang crimping untuk konektor RJ45 dan RJ11.', 'Baja karbon\nDilengkapi pemotong kabel', '6 bulan', 95000, 3, 400, ''],
      [cat('Aksesoris'), 'Konektor RJ45 Cat6 isi 100', 'Amp', 'Konektor RJ45 Cat6 per pak.', 'Cat6\nIsi 100 pcs\nKontak berlapis emas', '-', 120000, 40, 250, ''],
      [cat('Aksesoris'), 'LAN Tester RJ45', 'Generic', 'Penguji kabel LAN.', 'Uji RJ45/RJ11\nBaterai 9V', '3 bulan', 65000, 0, 200, ''],
      [cat('Kabel'), 'Kabel Fiber Optik Drop Core 1000m', 'ZTE', 'Kabel fiber optik drop wire single mode.', '1 Core\nPanjang 1000m', '-', 850000, 5, 20000, ''],
    ];
    const tx = db.transaction(() => rows.forEach((r) => ins.run(...r)));
    tx();
  }
}
seed();

module.exports = db;
