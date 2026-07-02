<?php 

include 'database/database.php';
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
$sql_anggota = mysqli_query($conn, "SELECT id_anggota, nama_anggota FROM anggota");
$sql_buku = mysqli_query($conn, "SELECT id_buku, judul_buku FROM buku");

?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="container">
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Tambah Buku</h3>
        </header>
        <div class="form-section">
          <form action="proses_tambah_peminjaman.php" method="POST">
            <label for="judul">Nama Anggota:</label>
            <select name="id_anggota" id="id_anggota" required>
                <option value="">-- Pilih Anggota --</option>
                <?php while($row = mysqli_fetch_assoc($sql_anggota)): ?>
                    <option value="<?php echo $row['id_anggota']; ?>"><?php echo $row['nama_anggota']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="pengarang">Judul Buku:</label>
            <select name="id_buku" id="id_buku" required>
                <option value="">-- Pilih Buku --</option>
                <?php while($row = mysqli_fetch_assoc($sql_buku)): ?>
                    <option value="<?php echo $row['id_buku']; ?>"><?php echo $row['judul_buku']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="tanggal_pinjam">Tanggal pinjam:</label>
            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required />

            <label for="tanggal_pengembalian">Tanggal pengembalian:</label>
            <input type="date" id="tanggal_pengembalian" name="tanggal_pengembalian" required />

            <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
          </form>
        </div>
      </main>
    </div>
  </body>
</html>
