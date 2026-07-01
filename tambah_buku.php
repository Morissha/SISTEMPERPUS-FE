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
$query_kategori = "SELECT id_kategori, nama_kategori FROM kategori";
$result_kategori = mysqli_query($conn, $query_kategori);

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
          <h3>Tambah Buku</h3>
        </header>
        <div class="form-section">
          <form action="proses_tambah_buku.php" method="POST" enctype="multipart/form-data">
            <label for="judul">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" required />

            <label for="pengarang">Pengarang:</label>
            <input type="text" id="pengarang" name="pengarang" required />

            <label for="penerbit">Penerbit:</label>
            <input type="text" id="penerbit" name="penerbit" required />

            <label for="tahun">Tahun Terbit:</label>
            <input type="number" id="tahun" name="tahun" required />

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php while($row = mysqli_fetch_assoc($result_kategori)): ?>
                    <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="stok">Stok:</label>
            <input type="number" id="stok" name="stok" required />

            <label for="foto">Foto Buku:</label>
            <input type="file" id="foto" name="foto" accept="image/*" />

            <div class="btn-group">
              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="data_buku.php"><button class="btn btn-secondary" type="button">Batal</button></a>
            </div>
          </form>
        </div>
      </main>
    </div>
        <script src="../script.js"></script>
  </body>
</html>
