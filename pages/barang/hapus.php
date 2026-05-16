<?php
require_once "../../config/database.php";

if (!isset($_GET["id"])) {
    header("Location: ../../public/index.php");
    exit;
}

$id = $_GET["id"];

$query = "DELETE FROM barang WHERE id = :id";
$stmt = $conn->prepare($query);
$stmt->bindParam(":id", $id);

if ($stmt->execute()) {
    header("Location: ../../public/index.php");
    exit;
} else {
    echo "Gagal menghapus data barang";
}