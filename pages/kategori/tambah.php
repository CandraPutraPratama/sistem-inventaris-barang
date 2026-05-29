<?php
require_once "../../config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kategori = trim($_POST["nama_kategori"]);

    if (!empty($nama_kategori)) {
        $query = "INSERT INTO kategori (nama_kategori) VALUES (:nama_kategori)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":nama_kategori", $nama_kategori);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Gagal menambahkan kategori";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=20260529">
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="../../assets/img/creativity-infinity-logo.png" alt="Creativity Infinity Logo" class="app-logo" width="56" height="56">
            <div class="header-text">
                <h1>Tambah Kategori</h1>
                <p>Tambahkan kategori baru untuk data barang</p>
            </div>
        </div>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input 
                        type="text" 
                        id="nama_kategori" 
                        name="nama_kategori" 
                        placeholder="Contoh: Elektronik"
                        required
                    >
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>
