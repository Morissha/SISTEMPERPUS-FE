<?php 
include '../database/database.php';
session_start();

      if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: ../login.php');
          exit();
      } 

    if ($_SESSION['role'] === 'anggota') {
      echo "<script>
            alert('Akses ditolak! Halaman ini hanya untuk admin.');
            window.history.back();
          </script>";
      exit;
      };

$sql_peminjaman = mysqli_query($conn, "SELECT p.id_peminjaman, a.nama_anggota, b.judul_buku, p.tanggal_pinjam, p.tanggal_pengembalian, p.status
FROM peminjaman p
JOIN anggota a ON p.id_anggota = a.id_anggota
JOIN buku b ON p.id_buku = b.id_buku
WHERE p.status = 'Dipinjam'");

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  </head>
  <body>
    <div class="container">
      <?php include '../layout/sidebar.php'; ?>
      <main>
        <header>
          <h3>Dashboard Admin</h3>
        </header>
        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Data Peminjaman</h3>
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
                <th>Nama Anggota</th>
                <th>Nama Buku</th>
                <th>Tanggal Dipinjam</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
              </tr>
            </thead>
            <>
              <?php $no = 1; while($row = mysqli_fetch_assoc($sql_peminjaman)): ?>
              <tr>
                <td><?= $no ?></td>
                <td><?= $row['nama_anggota'] ?></td>
                <td><?= $row['judul_buku'] ?></td>
                <td><?= $row['tanggal_pinjam'] ?></td>
                <td><?= $row['tanggal_pengembalian'] ?></td>
                <td><span class="<?= $row['status'] ?>"><?= $row['status'] ?></span></td>
              </tr>
            <?php $no++; endwhile; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
      <script src="../script.js"></script>
  </body>
</html>
