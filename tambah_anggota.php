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
    <title>Tambah Anggota</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  </head>
  <body>
    <div class="container">
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Tambah Anggota</h3>
        </header>
        <div class="form-section">
          <form action="proses_tambah_anggota.php" method="POST">
            <label for="judul">Nama Anggota:</label>
            <input type="text" id="nama_anggota" name="nama_anggota" required />

            <label for="nim">Nim:</label>
            <input type="text" id="nim" name="nim" required />
            <label for="jenis">Jenis Kelamin:</label>
            <select name="jenis_kelamin" id="jenis_kelamin" required>
                <option value="">-- Pilih Jenis Kelamin --</option>
                <option value="P">Perempuan</option>
                <option value="L">Laki-laki</option>
            </select>
            <label for="alamat">Alamat:</label>
            <input type="text" id="alamat" name="alamat" required />

            <label for="no_hp">Nomor Handphone:</label>
            <input type="text" id="no_hp" name="no_hp" required />

            <div class="btn-group">
              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="anggota.php"><button class="btn btn-secondary" type="button">Batal</button></a>
            </div>
          
          </form>
        </div>
      </main>
    </div>
        <script src="../script.js"></script>
  </body>
</html>
