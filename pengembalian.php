<?php
include '../database/database.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

$sql = "
    SELECT 
        p.id_peminjaman,
        p.tanggal_pinjam,
        p.tanggal_pengembalian,
        p.status,
        a.nama_anggota,
        b.judul_buku
    FROM peminjaman p
    JOIN anggota a ON p.id_anggota = a.id_anggota
    JOIN buku b ON p.id_buku = b.id_buku
    WHERE p.status IN ('Dipinjam', 'Diperpanjang')
    ORDER BY p.tanggal_pengembalian ASC
";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pengembalian Buku</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

<div class="container">
<?php include '../layout/sidebar.php'; ?>

<main>
<header>
    <h3>Pengembalian Buku</h3>
</header>

<?php if (isset($_GET['message']) && $_GET['message'] === 'success'): ?>
<div class="alert-success">Buku berhasil dikembalikan.</div>
<?php elseif (isset($_GET['message']) && $_GET['message'] === 'error'): ?>
<div class="alert-error">Gagal memproses pengembalian buku.</div>
<?php endif; ?>

<div class="table-section">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>Judul Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
<?php if (mysqli_num_rows($result) > 0): ?>
<?php $no = 1; while($row = mysqli_fetch_assoc($result)): ?>
<tr>
    <td><?= $no++; ?></td>
    <td><?= htmlspecialchars($row['nama_anggota']); ?></td>
    <td><?= htmlspecialchars($row['judul_buku']); ?></td>
    <td><?= htmlspecialchars($row['tanggal_pinjam']); ?></td>
    <td><?= htmlspecialchars($row['tanggal_pengembalian']); ?></td>
    <td>
        <span class="<?= htmlspecialchars($row['status']); ?>">
            <?= htmlspecialchars($row['status']); ?>
        </span>
    </td>
    <td>
        <form method="POST" action="proses_kembali.php" onsubmit="return confirm('Yakin buku dikembalikan?')">
            <input type="hidden" name="id_peminjaman" value="<?= $row['id_peminjaman']; ?>">
            <button type="submit" name="kembalikan" class="action-btn">
                <i class="fas fa-undo"></i> Kembalikan
            </button>
        </form>
    </td>
</tr>
<?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="7" style="text-align:center;">Tidak ada peminjaman aktif saat ini.</td>
</tr>
<?php endif; ?>
</tbody>
    </table>
</div>
</main>
</div>
<script src="../script.js"></script>
</body>
</html>

