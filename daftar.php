<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Anggota</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="daftar-container">
    <div class="left">
        <img src="img/logo.png" alt="logo">
        <p>Sistem Informasi Peminjaman Buku Perpustakaan</p>
    </div>
    <div class="right">
            <div class="form-daftar">   
    <form method="post" action="daftar_akun.php">
        <h3>Isi Identitas </h3>
        <label>Nama Anggota</label>
        <input type="text" name="nama_anggota" required>
        <label>NIM</label>
        <input type="text" name="nim" required>
        <label>Jenis Kelamin</label>
        <select name="jenis_kelamin" required>
            <option value="">-- Pilih --</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <label>Alamat</label>
        <textarea name="alamat" required></textarea>
        <label>No HP</label>
        <input type="text" name="no_hp" required>
        <button class="btn btn-primary" type="submit" name="lanjut">Lanjut</button>
        <p>Sudah Punya Akun? <a href="login.php">Login</a></p>
    </form>
    </div>
    </div>
</div>

</body>
</html>
