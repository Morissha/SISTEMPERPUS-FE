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
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Tambah Kategori</h3>
        </header>
        <div class="form-section">
          <form action="proses_tambah_kategori.php" method="POST">
            <label for="judul">Nama Kategori:</label>
            <input type="text" id="judul_buku" name="nama_kategori" required />

            <label for="pengarang">Deskripsi:</label>
            <input type="text" id="pengarang" name="deskripsi" required />
            <div class="btn-group">
              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="kategori.php"><button class="btn btn-secondary" type="button">Batal</button></a>
            </div>
          </form>
        </div>
      </main>
    </div>
        <script src="../script.js"></script>
  </body>
</html>
