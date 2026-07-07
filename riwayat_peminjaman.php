<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header("Location: ../login.php");
    exit();
}

$nim = $_SESSION['nim'];

$sql = "SELECT 
            p.id_peminjaman,
            b.judul_buku,
            k.nama_kategori AS kategori,
            p.tanggal_pinjam AS tgl_peminjaman,
            p.tanggal_pengembalian AS tgl_pengembalian,
            p.status
        FROM peminjaman p
        INNER JOIN anggota a ON p.id_anggota = a.id_anggota
        INNER JOIN buku b ON p.id_buku = b.id_buku
        INNER JOIN kategori k ON b.id_kategori = k.id_kategori
        WHERE a.nim = '$nim'
        ORDER BY p.tanggal_pinjam DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    die("Query Error : " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .status-dipinjam {
            background-color: #cfe2ff;
            color: #084298;
            border: 1px solid #b6d4fe;
        }
        .status-dikembalikan {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-hilang {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include '../layout/sidebar.php';?>
    <main>
        <header>
          <h3>Riwayat Peminjaman</h3>
        </header>
        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Daftar Buku Yang Sudah Dipinjam</h3>
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
                <th>Kategori</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              if (mysqli_num_rows($result) == 0) {
                  echo "<tr><td colspan='6' style='text-align: center; padding: 20px;'>Tidak ada riwayat peminjaman</td></tr>";
              }
               while($row = mysqli_fetch_assoc($result)): 
                $no++;
              ?>
              <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['judul_buku']; ?></td>
                <td><?php echo $row['kategori']; ?></td>
                <td><?php echo $row['tgl_peminjaman']; ?></td>
                <td><?php echo $row['tgl_pengembalian']; ?></td>
                <td><span class="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></span></td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
  </main>
</div>
</body>
</html>
