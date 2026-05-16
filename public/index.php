<?php
require_once "../config/database.php";

$query = "
    SELECT
        barang.id,
        barang.nama_barang,
        barang.kode_barang,
        barang.stok,
        barang.satuan,
        barang.lokasi,
        kategori.nama_kategori
    FROM barang
    LEFT JOIN kategori ON barang.kategori_id = kategori.id
    ORDER BY barang.id DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Barang</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sistem Inventaris Barang</h1>
            <p>Data inventaris barang kantor/organisasi</p>
        </div>

        <div class="top-bar">
            <a href="../pages/barang/tambah.php" class="btn btn-primary">+ Tambah Barang</a>
        </div>

        <div class="card">
            <h2>Data Barang</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Lokasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($barang) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($barang as $item): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($item['kode_barang']) ?></td>
                                <td><?= htmlspecialchars($item['nama_barang']) ?></td>
                                <td><?= htmlspecialchars($item['nama_kategori']) ?></td>
                                <td><?= htmlspecialchars($item['stok']) ?></td>
                                <td><?= htmlspecialchars($item['satuan']) ?></td>
                                <td><?= htmlspecialchars($item['lokasi']) ?></td>
                                <td>
                                    <a href="../pages/barang/edit.php?id=<?= $item['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="../pages/barang/hapus.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">Belum ada data barang.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>