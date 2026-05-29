<?php
require_once __DIR__ . "/../../config/database.php";

$queryKategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmtKategori = $conn->prepare($queryKategori);
$stmtKategori->execute();
$kategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

if (($_SERVER["REQUEST_METHOD"] ?? "GET") == "POST") {
    $kode_barang = $_POST["kode_barang"];
    $nama_barang = $_POST["nama_barang"];
    $kategori_id = $_POST["kategori_id"];
    $stok = $_POST["stok"];
    $satuan = $_POST["satuan"];
    $lokasi = $_POST["lokasi"];

    $query = "
    INSERT INTO barang
    (kode_barang, nama_barang, kategori_id, stok, satuan, lokasi)
    VALUES
    (:kode_barang, :nama_barang, :kategori_id, :stok, :satuan, :lokasi)
    ";

    $stmt = $conn->prepare($query);
    
    $stmt->bindParam(":kode_barang", $kode_barang);
    $stmt->bindParam(":nama_barang", $nama_barang);
    $stmt->bindParam(":kategori_id", $kategori_id);
    $stmt->bindParam(":stok", $stok);
    $stmt->bindParam(":satuan", $satuan);
    $stmt->bindParam(":lokasi", $lokasi);

    if ($stmt->execute()) {
        header("Location: ../../public/index.php");
        exit();
    } else {
        echo "Gagal menambahkan barang.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=20260529">
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="../../assets/img/creativity-infinity-logo.png" alt="Creativity Infinity Logo" class="app-logo" width="56" height="56">
            <div class="header-text">
                <h1>Tambah Barang</h1>
                <p>Masukkan data barang baru ke sistem inventaris</p>
            </div>
        </div>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input type="text" id="kode_barang" name="kode_barang" placeholder="Contoh: BRG004" required>
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" id="nama_barang" name="nama_barang" placeholder="Contoh: Printer Epson" required>
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach ($kategori as $item): ?>
                            <option value="<?= $item['id']; ?>">
                                <?= htmlspecialchars($item['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input type="number" id="stok" name="stok" min="0" placeholder="Contoh: 10" required>
                </div>

                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input type="text" id="satuan" name="satuan" placeholder="Contoh: unit / pcs / box" required>
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input type="text" id="lokasi" name="lokasi" placeholder="Contoh: Gudang Utama">
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="../../public/index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
