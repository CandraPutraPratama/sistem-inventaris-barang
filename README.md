# Sistem Inventaris Barang

Sistem Inventaris Barang adalah aplikasi web sederhana untuk mengelola data barang, kategori barang, stok masuk, stok keluar, dan riwayat transaksi stok. Aplikasi ini dibuat menggunakan PHP Native dan PostgreSQL.

## Fitur

- Dashboard ringkasan inventaris
- CRUD data barang
- CRUD kategori barang
- Pencarian barang berdasarkan nama, kode, kategori, atau lokasi
- Stok masuk
- Stok keluar
- Riwayat transaksi stok
- Validasi stok keluar agar tidak melebihi stok tersedia
- Tampilan sederhana dan responsif

## Teknologi yang Digunakan

- PHP Native
- PostgreSQL
- HTML
- CSS
- PDO PHP
- Laragon
- pgAdmin 4

## Struktur Folder

```text
sistem-inventaris-barang/
│
├── assets/
│   └── css/
│       └── style.css
│
├── config/
│   └── database.php
│
├── pages/
│   ├── barang/
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   └── hapus.php
│   │
│   ├── kategori/
│   │   ├── index.php
│   │   ├── tambah.php
│   │   ├── edit.php
│   │   └── hapus.php
│   │
│   └── stok/
│       ├── index.php
│       ├── masuk.php
│       └── keluar.php
│
├── public/
│   └── index.php
│
├── .gitignore
└── README.md
```

## Database

inventaris_barang

## Tabel Kategori

CREATE TABLE kategori (
id SERIAL PRIMARY KEY,
nama_kategori VARCHAR(100) NOT NULL
);

## Tabel Barang

CREATE TABLE barang (
id SERIAL PRIMARY KEY,
nama_barang VARCHAR(150) NOT NULL,
kode_barang VARCHAR(50) UNIQUE NOT NULL,
kategori_id INT REFERENCES kategori(id) ON DELETE SET NULL,
stok INT NOT NULL DEFAULT 0,
satuan VARCHAR(50) NOT NULL,
lokasi VARCHAR(100),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

## Tabel Stok Transaksi

CREATE TABLE stok_transaksi (
id SERIAL PRIMARY KEY,
barang_id INT REFERENCES barang(id) ON DELETE CASCADE,
jenis_transaksi VARCHAR(20) NOT NULL,
jumlah INT NOT NULL,
keterangan TEXT,
tanggal TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

## Data Awal

INSERT INTO kategori (nama_kategori) VALUES
('Elektronik'),
('Alat Tulis'),
('Peralatan Kantor'),
('Perabotan'),
('Lainnya');

## Contoh data barang:

INSERT INTO barang (nama_barang, kode_barang, kategori_id, stok, satuan, lokasi)
VALUES
('Laptop Lenovo ThinkPad', 'BRG001', 1, 5, 'unit', 'Ruang IT'),
('Pulpen Snowman', 'BRG002', 2, 50, 'pcs', 'Gudang ATK'),
('Meja Kantor', 'BRG003', 4, 10, 'unit', 'Ruang Administrasi');

## Cara Menjalankan Project

1. Clone repository ini:
   git clone https://github.com/username/sistem-inventaris-barang.git
2. Masuk ke folder project:
   cd sistem-inventaris-barang
3. Pindahkan folder project ke direktori Laragon:
   C:\laragon\www\
4. Buat database PostgreSQL dengan nama:
   inventaris_barang
5. Jalankan query pembuatan tabel yang ada dibagian Database.
6. Atur koneksi database di file:
   config/database.php
   Sesuaikan bagian berikut:
   $host = "localhost";
$port = "5432";
   $dbname = "inventaris_barang";
$user = "postgres";
   $password = "password_postgres_kamu";
7. Jalankan Laragon
8. Buka aplikasi di browser:
   http://localhost/sistem-inventaris-barang/public/

```

```
