<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header('Location: ../login.php');
    exit();
}
$sql = "SELECT b.id_buku, b.judul_buku, b.pengarang, b.penerbit, b.tahun, k.nama_kategori, b.stok FROM buku b JOIN kategori k ON b.id_kategori = k.id_kategori";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .btn-disabled {
            background-color: #ccc;
            color: #666;
            cursor: not-allowed;
            opacity: 0.6;
        }
        .btn-disabled:hover {
            background-color: #ccc;
        }
        .stok-habis {
            color: #dc3545;
            font-weight: bold;
        }
        .stok-tersedia {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
  <?php include '../layout/sidebar.php';?>
    <main>
        <header>
          <h3>Daftar Buku</h3>
        </header>
        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Data Buku</h3>
            </div>
            <div class="toolbar">
              <div class="left">
              </div>
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
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
               while($row = mysqli_fetch_assoc($result)):
                $no++;
               ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['judul_buku']; ?></td>
                <td><?php echo $row['pengarang']; ?></td>
                <td><?php echo $row['penerbit']; ?></td>
                <td><?php echo $row['tahun']; ?></td>
                <td><?php echo $row['nama_kategori']; ?></td>
                <td>
                  <?php 
                    if ($row['stok'] <= 0) {
                        echo "<span class='stok-habis'>Stok Habis</span>";
                    } else {
                        echo "<span class='stok-tersedia'>" . $row['stok'] . "</span>";
                    }
                  ?>
                </td>
                <td>
                  <?php 
                    if ($row['stok'] <= 0) {
                        echo "<button class='btn btn-tambah btn-disabled' disabled>Stok Habis</button>";
                    } else {
                        echo "<button class='btn btn-tambah'><a href='pengajuan_peminjaman.php?id_buku=" . $row['id_buku'] . "'>Ajukan Peminjaman</a></button>";
                    }
                  ?>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
  </main>
</div>
</body>
</html>