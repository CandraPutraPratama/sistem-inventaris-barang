<?php
require_once "../../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = $_GET["id"];

$queryKategori = "SELECT * FROM kategori WHERE id = :id";
$stmtKategori = $conn->prepare($queryKategori);
$stmtKategori->bindParam(":id", $id);
$stmtKategori->execute();
$kategori = $stmtKategori->fetch(PDO::FETCH_ASSOC);

if (!$kategori) {
    header("Location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_kategori = trim($_POST["nama_kategori"]);

    if (!empty($nama_kategori)) {
        $query = "
            UPDATE kategori 
            SET nama_kategori = :nama_kategori
            WHERE id = :id
        ";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":nama_kategori", $nama_kategori);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit;
        } else {
            echo "Gagal mengubah kategori";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Edit Kategori</h1>
            <p>Ubah data kategori barang</p>
        </div>

        <div class="card">
            <form action="" method="POST">
                <div class="form-group">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input 
                        type="text" 
                        id="nama_kategori" 
                        name="nama_kategori" 
                        value="<?= htmlspecialchars($kategori['nama_kategori']); ?>"
                        required
                    >
                </div>

                <div class="form-action">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>