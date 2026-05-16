<?php
require_once "../../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: ../../public/index.php");
    exit;
}

$id = $_GET["id"];

$queryBarang = "SELECT * FROM barang WHERE id = :id";
$stmtBarang = $conn->prepare($queryBarang);
$stmtBarang->bindParam(":id", $id);
$stmtBarang->execute();
$barang = $stmtBarang->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    header("Location: ../../public/index.php");
    exit;
}

$queryKategori = "SELECT * FROM kategori ORDER BY nama_kategori ASC";
$stmtKategori = $conn->prepare($queryKategori);
$stmtKategori->execute();
$kategori = $stmtKategori->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST["kode_barang"];
    $nama_barang = $_POST["nama_barang"];
    $kategori_id = $_POST["kategori_id"];
    $stok = $_POST["stok"];
    $satuan = $_POST["satuan"];
    $lokasi = $_POST["lokasi"];

    $query = "
        UPDATE barang 
        SET 
            kode_barang = :kode_barang,
            nama_barang = :nama_barang,
            kategori_id = :kategori_id,
            stok = :stok,
            satuan = :satuan,
            lokasi = :lokasi
        WHERE id = :id
    ";

    $stmt = $conn->prepare($query);

    $stmt->bindParam(":kode_barang", $kode_barang);
    $stmt->bindParam(":nama_barang", $nama_barang);
    $stmt->bindParam(":kategori_id", $kategori_id);
    $stmt->bindParam(":stok", $stok);
    $stmt->bindParam(":satuan", $satuan);
    $stmt->bindParam(":lokasi", $lokasi);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        header("Location: ../../public/index.php");
        exit;
    } else {
        echo "Gagal mengubah data barang";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Edit Barang</h1>
            <p>Ubah data barang yang sudah tersimpan</p>
        </div>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="kode_barang">Kode Barang</label>
                    <input 
                        type="text" 
                        id="kode_barang" 
                        name="kode_barang" 
                        value="<?= htmlspecialchars($barang['kode_barang']); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input 
                        type="text" 
                        id="nama_barang" 
                        name="nama_barang" 
                        value="<?= htmlspecialchars($barang['nama_barang']); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="kategori_id">Kategori</label>
                    <select id="kategori_id" name="kategori_id" required>
                        <option value="">-- Pilih Kategori --</option>

                        <?php foreach ($kategori as $item): ?>
                            <option 
                                value="<?= $item['id']; ?>"
                                <?= $item['id'] == $barang['kategori_id'] ? 'selected' : ''; ?>
                            >
                                <?= htmlspecialchars($item['nama_kategori']); ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="form-group">
                    <label for="stok">Stok</label>
                    <input 
                        type="number" 
                        id="stok" 
                        name="stok" 
                        min="0" 
                        value="<?= htmlspecialchars($barang['stok']); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="satuan">Satuan</label>
                    <input 
                        type="text" 
                        id="satuan" 
                        name="satuan" 
                        value="<?= htmlspecialchars($barang['satuan']); ?>" 
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="lokasi">Lokasi</label>
                    <input 
                        type="text" 
                        id="lokasi" 
                        name="lokasi" 
                        value="<?= htmlspecialchars($barang['lokasi']); ?>"
                    >
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="../../public/index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>