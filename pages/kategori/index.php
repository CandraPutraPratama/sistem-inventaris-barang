<?php
require_once "../../config/database.php";

$query = "
    SELECT 
        kategori.id,
        kategori.nama_kategori,
        COUNT(barang.id) AS jumlah_barang
    FROM kategori
    LEFT JOIN barang ON barang.kategori_id = kategori.id
    GROUP BY kategori.id, kategori.nama_kategori
    ORDER BY kategori.id DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$kategori = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kategori</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

    <div class="container">
        <div class="header">
            <h1>Kelola Kategori</h1>
            <p>Manajemen data kategori barang inventaris</p>
        </div>

        <div class="top-bar">
            <div>
                <a href="tambah.php" class="btn btn-primary">+ Tambah Kategori</a>
                <a href="../../public/index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="card">
            <h2>Data Kategori</h2>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Barang</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($kategori) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($kategori as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= htmlspecialchars($item['nama_kategori']); ?></td>
                                <td><?= htmlspecialchars($item['jumlah_barang']); ?></td>
                                <td>
                                    <a href="edit.php?id=<?= $item['id']; ?>" class="btn btn-warning">Edit</a>

                                    <?php if ($item['jumlah_barang'] == 0): ?>
                                        <a 
                                            href="hapus.php?id=<?= $item['id']; ?>" 
                                            class="btn btn-danger"
                                            onclick="return confirm('Yakin ingin menghapus kategori ini?')"
                                        >
                                            Hapus
                                        </a>
                                    <?php else: ?>
                                        <span class="badge-disabled">Tidak bisa dihapus</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada data kategori</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>