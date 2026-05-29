<?php
require_once __DIR__ . "/../config/database.php";

$queryTotalBarang = "SELECT COUNT(*) AS total FROM barang";
$stmtTotalBarang = $conn->prepare($queryTotalBarang);
$stmtTotalBarang->execute();
$totalBarang = $stmtTotalBarang->fetch(PDO::FETCH_ASSOC)['total'];

$queryTotalKategori = "SELECT COUNT(*) AS total FROM kategori";
$stmtTotalKategori = $conn->prepare($queryTotalKategori);
$stmtTotalKategori->execute();
$totalKategori = $stmtTotalKategori->fetch(PDO::FETCH_ASSOC)['total'];

$queryTotalStok = "SELECT COALESCE(SUM(stok), 0) AS total FROM barang";
$stmtTotalStok = $conn->prepare($queryTotalStok);
$stmtTotalStok->execute();
$totalStok = $stmtTotalStok->fetch(PDO::FETCH_ASSOC)['total'];

$queryStokMenipis = "SELECT COUNT(*) AS total FROM barang WHERE stok <= 5";
$stmtStokMenipis = $conn->prepare($queryStokMenipis);
$stmtStokMenipis->execute();
$stokMenipis = $stmtStokMenipis->fetch(PDO::FETCH_ASSOC)['total'];

$queryRiwayatTerbaru = "
    SELECT 
        stok_transaksi.jenis_transaksi,
        stok_transaksi.jumlah,
        stok_transaksi.tanggal,
        barang.nama_barang,
        barang.satuan
    FROM stok_transaksi
    INNER JOIN barang ON stok_transaksi.barang_id = barang.id
    ORDER BY stok_transaksi.tanggal DESC
    LIMIT 5
";

$stmtRiwayatTerbaru = $conn->prepare($queryRiwayatTerbaru);
$stmtRiwayatTerbaru->execute();
$riwayatTerbaru = $stmtRiwayatTerbaru->fetchAll(PDO::FETCH_ASSOC);

$keyword = $_GET["keyword"] ?? "";

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
    WHERE 
        barang.nama_barang ILIKE :keyword OR 
        barang.kode_barang ILIKE :keyword OR
        kategori.nama_kategori ILIKE :keyword OR
        barang.lokasi ILIKE :keyword
    ORDER BY barang.id DESC
";

$stmt = $conn->prepare($query);
$searchKeyword = '%' . $keyword . '%';
$stmt->bindParam(":keyword", $searchKeyword);
$stmt->execute();
$barang = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Inventaris Barang</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=20260529">
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="../assets/img/creativity-infinity-logo.png" alt="Creativity Infinity Logo" class="app-logo" width="56" height="56">
            <div class="header-text">
                <h1>Sistem Inventaris Barang</h1>
                <p>Data inventaris barang kantor/organisasi</p>
            </div>
        </div>

        <div class="dashboard-grid">
            <div class="dashboard-card">
                <h3>Total Barang</h3>
                <p><?= htmlspecialchars($totalBarang); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Total Kategori</h3>
                <p><?= htmlspecialchars($totalKategori); ?></p>
            </div>

            <div class="dashboard-card">
                <h3>Total Stok</h3>
                <p><?= htmlspecialchars($totalStok); ?></p>
            </div>

            <div class="dashboard-card warning-card">
                <h3>Stok Menipis</h3>
                <p><?= htmlspecialchars($stokMenipis); ?></p>
            </div>
        </div>

        <div class="card latest-card">
            <h2>Riwayat Stok Terbaru</h2>

            <?php if (count($riwayatTerbaru) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jenis</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayatTerbaru as $item): ?>
                            <tr>
                                <td><?= date('d-m-Y H:i', strtotime($item['tanggal'])); ?></td>
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
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Belum ada riwayat stok terbaru</p>
            <?php endif; ?>
        </div>
        
        <div class="top-bar">
            <div>
                <a href="../pages/barang/tambah.php" class="btn btn-primary">+ Tambah Barang</a>
                <a href="../pages/kategori/index.php" class="btn btn-secondary">Kelola Kategori</a>
                <a href="../pages/stok/index.php" class="btn btn-secondary">Riwayat Stok</a>
            </div>
            <form action="" method="GET" class="search-form">
                <input 
                    type="text" 
                    name="keyword" 
                    placeholder="Cari barang..." 
                    value="<?= htmlspecialchars($keyword); ?>"
                >
                <button type="submit" class="btn btn-primary">Cari</button>

                <?php if (!empty($keyword)): ?>
                    <a href="index.php" class="btn btn-secondary">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card">
            <h2>Data Barang</h2>

            <?php if (!empty($keyword)): ?>
                <p class="search-info">
                    Hasil pencarian untuk: <strong><?= htmlspecialchars($keyword); ?></strong>
                </p>
            <?php endif; ?>
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
                                    <a href="../pages/stok/masuk.php?id=<?= $item['id']; ?>" class="btn btn-success">Stok Masuk</a>
                                    <a href="../pages/stok/keluar.php?id=<?= $item['id']; ?>" class="btn btn-dark">Stok Keluar</a>
                                    <a href="../pages/barang/edit.php?id=<?= $item['id'] ?>" class="btn btn-warning">Edit</a>
                                    <a href="../pages/barang/hapus.php?id=<?= $item['id'] ?>" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center">
                                <?= !empty($keyword) ? 'Data barang tidak ditemukan' : 'Belum ada data barang'; ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>
