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
$id_buku = $_GET['id'];
$sql = "SELECT b.id_buku, b.judul_buku, b.pengarang, b.penerbit, b.tahun, k.nama_kategori, k.id_kategori, b.stok, b.foto FROM buku b JOIN kategori k ON b.id_kategori = k.id_kategori WHERE id_buku = $id_buku";
$buku = mysqli_fetch_assoc(mysqli_query($conn, $sql));
$result_kategori = mysqli_query($conn, "SELECT * FROM kategori");
?>



<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Buku</title>
    <link rel="stylesheet" href="../style.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    <div class="container">
<?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Edit Buku</h3>
        </header>
        <div class="form-section">
          <form action="proses_edit_buku.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_buku" value="<?php echo $buku['id_buku']; ?>" />
            <input type="hidden" name="foto_lama" value="<?php echo $buku['foto']; ?>" />
            
            <label for="judul">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?php echo $buku['judul_buku']; ?>" required />

            <label for="pengarang">Pengarang:</label>
            <input type="text" id="pengarang" name="pengarang" value="<?php echo $buku['pengarang']; ?>" required />

            <label for="penerbit">Penerbit:</label>
            <input type="text" id="penerbit" name="penerbit" value="<?php echo $buku['penerbit']; ?>" required />

            <label for="tahun">Tahun Terbit:</label>
            <input type="number" id="tahun" name="tahun" value="<?php echo $buku['tahun']; ?>" required />

            <label for="kategori">Kategori:</label>
            <select name="kategori" id="kategori" required>
                <option value="">-- Pilih Kategori --</option>
                <?php 
                $result_kategori = mysqli_query($conn, "SELECT * FROM kategori");
                while($row = mysqli_fetch_assoc($result_kategori)): 
                    $selected = ($row['id_kategori'] == $buku['id_kategori']) ? 'selected' : '';
                ?>
                    <option value="<?php echo $row['id_kategori']; ?>" <?php echo $selected; ?>><?php echo $row['nama_kategori']; ?></option>
                <?php endwhile; ?>
            </select>

            <label for="stok">Stok:</label>
            <input type="number" id="stok" name="stok" value="<?php echo $buku['stok']?>" required />

            <label for="foto">Foto Buku:</label>
            <?php if($buku['foto']): ?>
                <div style="margin-bottom: 10px;">
                    <img src="../img/<?php echo $buku['foto']; ?>" style="max-width: 100px; max-height: 150px;">
                    <p style="font-size: 12px; color: #666;">Foto saat ini</p>
                </div>
            <?php endif; ?>
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
        <script>
          document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const pesan = urlParams.get('pesan');
            
            if(pesan === 'sukses_edit') {
              Swal.fire({
                title: 'Berhasil!',
                text: 'Buku berhasil diperbarui',
                icon: 'success',
                confirmButtonText: 'OK'
              }).then(() => {
                window.location.href = 'data_buku.php';
              });
            } else if(pesan === 'error') {
              Swal.fire({
                title: 'Gagal!',
                text: 'Gagal memperbarui buku',
                icon: 'error',
                confirmButtonText: 'OK'
              });
            }
          });
        </script>
  </body>
</html>
