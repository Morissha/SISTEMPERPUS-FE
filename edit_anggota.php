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
$id_anggota = $_GET['id_anggota'];
$sql = "SELECT * FROM anggota WHERE id_anggota = '$id_anggota'";
$anggota = mysqli_fetch_assoc(mysqli_query($conn, $sql));
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Anggota</title>
    <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="container">
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Edit Anggota</h3>
        </header>
        <div class="form-section">
          <form action="proses_edit_anggota.php" method="POST">
            <input type="hidden" name="id_anggota" value="<?php echo $anggota['id_anggota']; ?>" />
            <label for="judul">Nama Anggota:</label>
            <input type="text" id="nama_anggota" name="nama_anggota" value="<?php echo $anggota['nama_anggota']; ?>" required />

            <label for="pengarang">NIM:</label>
            <input type="text" id="nim" name="nim" value="<?php echo $anggota['nim']; ?>" required />
            <label for="penerbit">Jenis Kelamin:</label>
            <select name="jenis_kelamin" id="jenis_kelamin">
                                <option value="Perempuan" <?php echo ($anggota['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                <option value="Laki-laki" <?php echo ($anggota['jenis_kelamin'] == 'Laki-laki') ? 'selected' : ''; ?>>Laki-laki</option>
            </select>
            <label for="tahun">No .HP:</label>
            <input type="number" id="tahun" name="tahun" value="<?php echo $anggota['no_hp']; ?>" required />
            <div class="btn-group">
                              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="anggota.php"><button class="btn btn-secondary" type="button">Batal</button></a>
            </div>
            </div>
          </form>
        </div>
      </main>
    </div>
        <script src="../script.js"></script>
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pesan = urlParams.get('pesan');
            
            if(pesan === 'sukses_edit') {
              Swal.fire({
                title: 'Berhasil!',
                text: 'Anggota berhasil diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.href = 'anggota.php';
              });
            } else if(pesan === 'error') {
              Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memperbarui anggota',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        </script>
  </body>
</html>
