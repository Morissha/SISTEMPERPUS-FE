<?php
include '../database/database.php';
session_start();

// 1. AMBIL DATA LAMA UNTUK DITAMPILKAN DI FORM
if (isset($_GET['id'])) {
    $id_pengajuan = $_GET['id'];
    // Ambil data pengajuan berdasarkan ID yang dikirim melalui URL
    $query_select = mysqli_query($conn, "SELECT * FROM pengajuan_peminjaman WHERE id_pengajuan_peminjaman = '$id_pengajuan'");
    $pengajuan = mysqli_fetch_assoc($query_select);
    
    if (!$pengajuan) {
        echo "<script>alert('Data tidak ditemukan!'); window.location.href='pengajuan_saya.php';</script>";
        exit;
    }
} else {
    header('Location: pengajuan_saya.php');
    exit;
}

// 2. PROSES UPDATE KETIKA TOMBOL SIMPAN DITEKAN
if (isset($_POST['simpan'])) {
    $id_pengajuan = $_POST['id_pengajuan']; 
    $tgl_peminjaman = $_POST['tgl_peminjaman'];
    $tgl_pengembalian = $_POST['tgl_pengembalian'];

    // Query diperbaiki agar nama field kolomnya sesuai dengan name input form Anda
    $query_update = mysqli_query($conn, "UPDATE pengajuan_peminjaman 
        SET tgl_peminjaman = '$tgl_peminjaman', tgl_pengembalian = '$tgl_pengembalian' 
        WHERE id_pengajuan_peminjaman = '$id_pengajuan'");

    if ($query_update) {
        echo "<script>
            alert('Data pengajuan berhasil diperbarui!');
            window.location.href = 'pengajuan_saya.php'; 
        </script>";
        exit;
    } else {
        echo "<script>
            alert('Gagal menyimpan perubahan: " . mysqli_error($conn) . "');
            window.history.back();
        </script>";
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Pengajuan</title>
    <link rel="stylesheet" href="../style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
  </head>
  <body>
    <div class="container">
      <?php include '../layout/sidebar.php';?>
      <main>
        <header>
          <h3>Edit Pengajuan</h3>
        </header>
        <div class="form-section">
          <form action="" method="POST">
            
            <input type="hidden" name="id_pengajuan" value="<?php echo $pengajuan['id_pengajuan_peminjaman']; ?>" />

            <label for=\"judul\">Judul Buku:</label>
            <input type="text" id="judul_buku" name="judul_buku" value="<?php echo htmlspecialchars($pengajuan['judul_buku']); ?>" readonly />

            <label for="pengarang">kategori:</label>
            <input type="text" id="pengarang" name="pengarang" value="<?php echo htmlspecialchars($pengajuan['kategori']); ?>" readonly />

            <label for="tgl_peminjaman">Tanggal Peminjaman:</label>
            <input type="date" id="tgl_peminjaman" name="tgl_peminjaman" value="<?php echo $pengajuan['tgl_peminjaman']; ?>" required />

            <label for="tgl_pengembalian">Tanggal Pengembalian:</label>
            <input type="date" id="tgl_pengembalian" name="tgl_pengembalian" value="<?php echo $pengajuan['tgl_pengembalian']; ?>" required />

            <div class="btn-group" style="margin-top: 20px;">
              <button class="btn btn-tambah" type="submit" name="simpan">Simpan</button>
              <a href="pengajuan_saya.php"><button class="btn btn-secondary" type="button">Batal</button></a>
            </div>
          </form>
        </div>
      </main>
    </div>
    <script src="../script.js"></script>
  </body>
</html>