<?php
require_once "../../config/database.php";

$query = "
    SELECT 
        stok_transaksi.id,
        stok_transaksi.jenis_transaksi,
        stok_transaksi.jumlah,
        stok_transaksi.keterangan,
        stok_transaksi.tanggal,
        barang.kode_barang,
        barang.nama_barang,
        barang.satuan
    FROM stok_transaksi
    INNER JOIN barang ON stok_transaksi.barang_id = barang.id
    ORDER BY stok_transaksi.tanggal DESC
";

$stmt = $conn->prepare($query);
$stmt->execute();
$transaksi = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Stok</title>
    <link rel="stylesheet" href="../../assets/css/style.css?v=20260529">
</head>
<body>

    <div class="container">
        <div class="header">
            <img src="../../assets/img/creativity-infinity-logo.png" alt="Creativity Infinity Logo" class="app-logo" width="56" height="56">
            <div class="header-text">
                <h1>Riwayat Stok</h1>
                <p>Catatan transaksi stok masuk dan stok keluar</p>
            </div>
        </div>

        <div class="top-bar">
            <div>
                <a href="../../public/index.php" class="btn btn-secondary">Kembali</a>
            </div>
        </div>

        <div class="card">
            <h2>Data Riwayat Stok</h2>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($transaksi) > 0): ?>
                        <?php $no = 1; ?>
                        <?php foreach ($transaksi as $item): ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= date('d-m-Y H:i', strtotime($item['tanggal'])); ?></td>
                                <td><?= htmlspecialchars($item['kode_barang']); ?></td>
                                <td><?= htmlspecialchars($item['nama_barang']); ?></td>
                                <td>
                                    <?php if ($item['jenis_transaksi'] == 'masuk'): ?>
                                        <span class="badge badge-success">Masuk</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Keluar</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= htmlspecialchars($item['jumlah']); ?> 
                                    <?= htmlspecialchars($item['satuan']); ?>
                                </td>
                                <td><?= htmlspecialchars($item['keterangan'] ?: '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">Belum ada riwayat stok</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
