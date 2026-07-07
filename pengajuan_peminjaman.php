<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header('Location: ../login.php');
    exit();
}

$id_buku = $_GET['id_buku'];
$query = mysqli_query($conn, "
    SELECT 
        b.judul_buku,
        b.pengarang,
        b.penerbit,
        b.tahun,
        k.nama_kategori
    FROM buku b
    JOIN kategori k ON b.id_kategori = k.id_kategori
    WHERE b.id_buku = '$id_buku'
");


$buku = mysqli_fetch_assoc($query);


?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengajuan Peminjaman</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
      .form-section input[readonly] {
        background-color: #f5f5f5;
        color: #666;
        cursor: not-allowed;
      }
      .alert {
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
      }
      .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
      }
      .alert-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Pengajuan Peminjaman</h3>
        </header>
        <div class="form-section">
          <form action="proses_pengajuan_peminjaman.php" method="POST">
            <label for="nama_anggota">Nama Anggota:</label>
            <input type="text" id="nama_anggota" name="nama_anggota" value="<?php echo $_SESSION['nama_anggota']; ?>" readonly />
            <label for="nama_anggota">Nim:</label>
            <input type="text" id="nama_anggota" name="nim" value="<?php echo $_SESSION['nim']; ?>" readonly />
            <label for="nama_anggota">Judul Buku:</label>
            <input type="text" id="nama_anggota" name="judul_buku" value="<?php echo $buku['judul_buku']; ?>" readonly />
            <label for="nama_anggota">Kategori:</label>
            <input type="text" id="nama_anggota" name="kategori" value="Peminjaman" readonly />
            <label for="nama_anggota">Tgl Peminjaman:</label>
            <input type="date" id="nama_anggota" name="tgl_peminjaman"  />
            <label for="nama_anggota">Tgl Pengembalian:</label>
            <input type="date" id="nama_anggota" name="tgl_pengembalian"/>
            <div class="btn-group">
                <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
                <button class="btn btn-edit"><a href="peminjaman.php">Kembali</a></button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </body>
</html>
