<?php
require_once "../../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: ../../public/index.php");
    exit;
}

$id = $_GET["id"];

$queryBarang = "
    SELECT 
        barang.*,
        kategori.nama_kategori
    FROM barang
    LEFT JOIN kategori ON barang.kategori_id = kategori.id
    WHERE barang.id = :id
";

$stmtBarang = $conn->prepare($queryBarang);
$stmtBarang->bindParam(":id", $id);
$stmtBarang->execute();
$barang = $stmtBarang->fetch(PDO::FETCH_ASSOC);

if (!$barang) {
    header("Location: ../../public/index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jumlah = (int) $_POST["jumlah"];
    $keterangan = trim($_POST["keterangan"]);

    if ($jumlah <= 0) {
        $error = "Jumlah stok keluar harus lebih dari 0";
    } elseif ($jumlah > $barang["stok"]) {
        $error = "Jumlah stok keluar tidak boleh melebihi stok saat ini";
    } else {
        try {
            $conn->beginTransaction();

            $queryUpdate = "
                UPDATE barang
                SET stok = stok - :jumlah
                WHERE id = :id
            ";

            $stmtUpdate = $conn->prepare($queryUpdate);
            $stmtUpdate->bindParam(":jumlah", $jumlah);
            $stmtUpdate->bindParam(":id", $id);
            $stmtUpdate->execute();

            $jenis_transaksi = "keluar";

            $queryTransaksi = "
                INSERT INTO stok_transaksi 
                (barang_id, jenis_transaksi, jumlah, keterangan)
                VALUES
                (:barang_id, :jenis_transaksi, :jumlah, :keterangan)
            ";

            $stmtTransaksi = $conn->prepare($queryTransaksi);
            $stmtTransaksi->bindParam(":barang_id", $id);
            $stmtTransaksi->bindParam(":jenis_transaksi", $jenis_transaksi);
            $stmtTransaksi->bindParam(":jumlah", $jumlah);
            $stmtTransaksi->bindParam(":keterangan", $keterangan);
            $stmtTransaksi->execute();

            $conn->commit();

            header("Location: ../../public/index.php");
            exit;
        } catch (PDOException $e) {
            $conn->rollBack();
            $error = "Gagal memproses stok keluar: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stok Keluar</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Stok Keluar</h1>
            <p>Kurangi jumlah stok barang</p>
        </div>

        <div class="card">
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="detail-box">
                <p><strong>Kode Barang:</strong> <?= htmlspecialchars($barang['kode_barang']); ?></p>
                <p><strong>Nama Barang:</strong> <?= htmlspecialchars($barang['nama_barang']); ?></p>
                <p><strong>Kategori:</strong> <?= htmlspecialchars($barang['nama_kategori'] ?? '-'); ?></p>
                <p><strong>Stok Saat Ini:</strong> <?= htmlspecialchars($barang['stok']); ?> <?= htmlspecialchars($barang['satuan']); ?></p>
            </div>

            <form action="" method="POST">
                <div class="form-group">
                    <label for="jumlah">Jumlah Stok Keluar</label>
                    <input 
                        type="number" 
                        id="jumlah" 
                        name="jumlah" 
                        min="1" 
                        max="<?= htmlspecialchars($barang['stok']); ?>"
                        placeholder="Contoh: 2"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="keterangan">Keterangan</label>
                    <input 
                        type="text" 
                        id="keterangan" 
                        name="keterangan" 
                        placeholder="Contoh: Dipakai untuk kegiatan"
                    >
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-dark">Simpan Stok Keluar</button>
                    <a href="../../public/index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>