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

$sql = "SELECT * FROM anggota";
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
              <h3>Data Anggota</h3>
            </div>
            <div class="toolbar">
              <div class="left">
                <a href="tambah_anggota.php"><button class="btn btn-tambah">+Tambah Anggota</button></a>
              </div>
              <div class="right">
                <input type="text" placeholder="Cari Anggota..." data-search-table />
              </div>
            </div>
          </div>
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Anggota</th>
                <th>NIM</th>
                <th>Jenis Kelamin</th>
                <th>Alamat</th>
                <th>No. HP</th>
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
                <td><?php echo $row['nama_anggota']; ?></td>
                <td><?php echo $row['nim']; ?></td>
                <td><?php echo $row['jenis_kelamin']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td><?php echo $row['no_hp']; ?></td>

                <td>
                  <button class="btn btn-edit"><a href="edit_anggota.php?id_anggota=<?php echo $row['id_anggota']; ?>">Edit</a></button>
                  <a href="proses_hapus_anggota.php?id_anggota=<?php echo $row['id_anggota']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?');"><button class="btn btn-hapus">Hapus</button></a>
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
                text: 'Anggota berhasil dihapus',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
              });
            } else if(pesan === 'sukses_edit') {
              Swal.fire({
                title: 'Berhasil!',
                text: 'Anggota berhasil diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.history.replaceState({}, document.title, window.location.pathname);
              });
            } else if(pesan === 'error') {
              Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus anggota',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        </script>
  </body>
</html>
