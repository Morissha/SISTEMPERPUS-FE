<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header('Location: ../login.php');
    exit();
}

// Ambil data peminjaman milik anggota yang login
$sql = "SELECT 
            p.id_peminjaman,
            a.nama_anggota,
            b.judul_buku,
            p.tanggal_pinjam,
            p.tanggal_pengembalian,
            p.status
        FROM peminjaman p
        JOIN anggota a ON p.id_anggota = a.id_anggota
        JOIN buku b ON p.id_buku = b.id_buku
        WHERE a.nim = '" . $_SESSION['nim'] . "'";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpanjangan Buku</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>

<div class="container">
    <?php include '../layout/sidebar.php'; ?>

    <main>
        <header>
            <h3>Perpanjangan Buku</h3>
        </header>

        <div class="table-section">
            <div class="table-header">
                <div class="tittle">
                    <h3>Data Buku</h3>
                </div>

                <div class="toolbar">
                    <div class="right">
                        <input type="text" placeholder="Cari Buku..." data-search-table />
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Judul Buku</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;

                    if (mysqli_num_rows($result) == 0) {
                        echo "<tr>
                                <td colspan='5' style='text-align:center;'>
                                    Tidak ada data peminjaman.
                                </td>
                              </tr>";
                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                    ?>
                            <tr>
                                <td><?= $no++; ?></td>
                                <td><?= $row['judul_buku']; ?></td>
                                <td><?= $row['tanggal_pinjam']; ?></td>
                                <td><?= $row['tanggal_pengembalian']; ?></td>
                                <td>
                                    <a href="pengajuan_perpanjangan.php?id=<?= $row['id_peminjaman']; ?>">
                                        <button class="btn btn-edit">
                                            Ajukan Perpanjangan
                                        </button>
                                    </a>
                                </td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>