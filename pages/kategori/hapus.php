<?php
require_once "../../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: index.php");
    exit;
}

$id = $_GET["id"];

$queryCek = "SELECT COUNT(*) AS total FROM barang WHERE kategori_id = :id";
$stmtCek = $conn->prepare($queryCek);
$stmtCek->bindParam(":id", $id);
$stmtCek->execute();
$result = $stmtCek->fetch(PDO::FETCH_ASSOC);

if ($result['total'] > 0) {
    header("Location: index.php");
    exit;
}

$query = "DELETE FROM kategori WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    header("Location: index.php");
    exit;
} else {
    echo "Gagal menghapus kategori";
}