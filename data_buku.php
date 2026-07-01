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

$sql = "SELECT b.id_buku, b.judul_buku, b.pengarang, b.penerbit, b.tahun, k.nama_kategori, b.stok, b.foto FROM buku b JOIN kategori k ON b.id_kategori = k.id_kategori";
$result = mysqli_query($conn, $sql);
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>
  <body>
    <div class="container">
      <?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Dashboard Admin</h3>
        </header>
        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Data Buku</h3>
            </div>
            <div class="toolbar">
              <div class="left">
                <a href="tambah_buku.php"><button class="btn btn-tambah">+Tambah Buku</button></a>
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
                <th>Foto</th>
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
                <td><?php echo $no?></td>
                <td><?php echo $row['judul_buku']; ?></td>
                <td><?php echo $row['pengarang']; ?></td>
                <td><?php echo $row['penerbit']; ?></td>
                <td><?php echo $row['tahun']; ?></td>
                <td><?php echo $row['nama_kategori']; ?></td>
                <td><?php echo $row['stok']; ?></td>
                <td><?php if($row['foto']) { ?><img src="../img/<?php echo $row['foto']; ?>" style="max-width: 50px; max-height: 70px;"><?php } else { echo '-'; } ?></td>
                <td>
                  <a href="edit_buku.php?id=<?php echo $row['id_buku']; ?>"><button class="btn btn-edit">Edit</button></a>
                  <a href="proses_hapus_buku.php?id=<?php echo $row['id_buku']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');"><button class="btn btn-hapus">Hapus</button></a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </main>
    </div>
        <script src="../script.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pesan = urlParams.get('pesan');
            
            if(pesan === 'sukses') {
              Swal.fire({
                title: 'Berhasil!',
                text: 'Buku berhasil dihapus',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
              });
            } else if(pesan === 'sukses_edit') {
              Swal.fire({
                title: 'Berhasil!',
                text: 'Buku berhasil diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
              });
            } else if(pesan === 'error') {
              Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus buku',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        </script>
  </body>
</html>
