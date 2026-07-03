<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header('Location: ../login.php');
    exit();
}
$sql = " SELECT * FROM pengajuan_peminjaman ";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengajuan Saya</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .status-badge {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
            display: inline-block;
        }
        .status-menunggu {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .status-disetujui, .status-diterima {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .status-ditolak {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .tipe-badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 12px;
            display: inline-block;
        }
        .tipe-peminjaman {
            background-color: #e3f2fd;
            color: #1565c0;
        }
        .tipe-perpanjangan {
            background-color: #f3e5f5;
            color: #6a1b9a;
        }
    </style>
</head>
<body>

<div class="container">
  <?php include '../layout/sidebar.php';?>
    <main>
        <header>
          <h3>Pengajuan Saya</h3>
        </header>
        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Daftar Pengajuan Peminjaman dan Perpanjangan</h3>
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
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              if (mysqli_num_rows($result) == 0) {
                  echo "<tr><td colspan='7' style='text-align: center; padding: 20px;'>Tidak ada pengajuan</td></tr>";
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
                <td>
                  <button class="btn btn-primary"><a href="edit_pengajuan.php?id_pengajuan_peminjaman=<?php echo $row['id_pengajuan_peminjaman']; ?>">Edit</a></button>
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