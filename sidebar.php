<?php
if($_SESSION['role'] == 'anggota') {
     echo "<aside class='sidebar'>";
        echo "<nav>";
          echo "<ul>";
                      echo "<li><a href='../anggota/index.php'><i class='fas fa-book-open'></i> Daftar Buku </a></li>";
            echo "<li><a href='../anggota/pengajuan_saya.php'><i class='fas fa-paper-plane'></i> Pengajuan Saya</a></li>";
            echo "<li><a href='../anggota/riwayat_peminjaman.php'><i class='fas fa-history'></i> Riwayat Peminjaman</a></li>";
            echo "<li><a href='../anggota/perpanjangan.php'><i class='fas fa-redo'></i> Perpanjangan</a></li>";
            echo "<li><a href='../anggota/denda.php'><i class='fas fa-money-bill-wave'></i> Denda Saya</a></li>";
            echo "<li><a href='../logout.php'><i class='fas fa-sign-out-alt'></i> Logout</a></li>";
          echo "</ul>";
        echo "</nav>";
    echo "</aside>";
} else if ($_SESSION['role'] == 'admin') {
      echo "<aside class='sidebar'>";
        echo "<nav>";
          echo "<ul>";
            echo "<li><a href='../admin/index.php'><i class='fas fa-chart-line'></i> Dashboard</a></li>";
            echo "<li><a href='../admin/data_buku.php'><i class='fas fa-book-open'></i> Data Buku</a></li>";
            echo "<li><a href='../admin/kategori.php'><i class='fas fa-list'></i> Kategori</a></li>";
            echo "<li><a href='../admin/anggota.php'><i class='fas fa-users'></i> Data Anggota</a></li>";
            
            // Dropdown Menu Transaksi
            echo "<li class='dropdown'>";
            echo "  <a href='javascript:void(0)' class='dropdown-toggle'><i class='fas fa-exchange-alt'></i> Transaksi <i class='fas fa-chevron-down dropdown-icon'></i></a>";
            echo "  <ul class='dropdown-menu'>";
            echo "    <li><a href='../admin/verifikasi_peminjaman.php'><i class='fas fa-check-circle'></i> Verifikasi Pengajuan</a></li>";
            echo "    <li><a href='../admin/peminjaman.php'><i class='fas fa-book-reader'></i> Data Peminjaman</a></li>";
            echo "    <li><a href='../admin/pengembalian.php'><i class='fas fa-undo'></i> Pengembalian Buku</a></li>";
            echo "    <li><a href='../admin/perpanjangan.php'><i class='fas fa-redo-alt'></i> Data Perpanjangan</a></li>";
            echo "    <li><a href='../admin/denda.php'><i class='fas fa-exclamation-triangle'></i> Data Denda</a></li>";
            echo "  </ul>";
            echo "</li>";
            
            echo "<li><a href='../admin/laporan.php'><i class='fas fa-file-alt'></i> Laporan</a></li>";
            echo "<li><a href='../logout.php'><i class='fas fa-sign-out-alt'></i> Logout</a></li>";
          echo "</ul>";
        echo "</nav>";
    echo "</aside>";
} else {
  echo"Role tidak dikenali!!";
}


?>