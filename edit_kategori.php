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
$id_kategori = $_GET['id_kategori'];
$query = "SELECT * FROM kategori WHERE id_kategori = $id_kategori";
$result = mysqli_query($conn, $query);
$kategori = mysqli_fetch_assoc($result);
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Kategori</title>
    <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="container">
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Edit Kategori</h3>
        </header>
        <div class="form-section">
          <form action="proses_edit_kategori.php" method="POST">
            <input type="hidden" name="id_kategori" value="<?php echo $kategori['id_kategori']; ?>" />
            <label for="judul">Nama Kategori:</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?php echo $kategori['nama_kategori']; ?>" required />

            <label for="pengarang">Deskripsi:</label>
            <input type="text" id="pengarang" name="pengarang" value="<?php echo $kategori['deskripsi']; ?>" required />
            <div class="btn-group">
              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="kategori.php"><button class="btn btn-secondary" type="button">Batal</button></a>
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
                text: 'Kategori berhasil diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.href = 'kategori.php';
              });
            } else if(pesan === 'error') {
              Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memperbarui kategori',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        </script>
  </body>
</html>
