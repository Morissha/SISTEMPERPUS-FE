<?php 
      session_start();
      
      // Cek apakah user sudah login
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
      include '../database/database.php';
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link rel="stylesheet" href="../style.css" />
    <style>
      .dashboard-content {
        padding: 0;
      }

      .stats-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 24px;
        padding: 30px;
      }

      .stat-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-md);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 20px;
      }

      .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-xl);
      }

      .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        color: white;
      }

      .stat-icon.blue {
        background: linear-gradient(135deg, #2563eb, #3b82f6);
      }

      .stat-icon.green {
        background: linear-gradient(135deg, #10b981, #34d399);
      }

      .stat-icon.orange {
        background: linear-gradient(135deg, #f59e0b, #fbbf24);
      }

      .stat-icon.red {
        background: linear-gradient(135deg, #ef4444, #f87171);
      }

      .stat-content h3 {
        font-size: 14px;
        color: var(--gray-text);
        font-weight: 500;
        margin: 0;
        margin-bottom: 8px;
      }

      .stat-content p {
        font-size: 32px;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
      }

      .recent-section {
        padding: 30px;
      }

      .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
      }

      .section-header h2 {
        font-size: 24px;
        font-weight: 700;
        color: var(--dark-text);
        margin: 0;
      }

      .section-header a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
      }

      .section-header a:hover {
        color: var(--primary-dark);
      }

      .recent-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-md);
      }

      .recent-table table {
        width: 100%;
        border-collapse: collapse;
      }

      .recent-table th {
        background: var(--light-bg);
        padding: 16px;
        font-weight: 600;
        color: var(--dark-text);
        text-align: left;
        border-bottom: 2px solid var(--border-color);
      }

      .recent-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
      }

      .recent-table tr:last-child td {
        border-bottom: none;
      }

      .recent-table tr:hover {
        background: var(--light-bg);
      }

      .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
      }

      .status-menunggu {
        background: #fef3c7;
        color: #92400e;
      }

      .status-disetujui {
        background: #d1fae5;
        color: #065f46;
      }

      .status-ditolak {
        background: #fee2e2;
        color: #7f1d1d;
      }

      .no-data {
        text-align: center;
        padding: 40px;
        color: var(--gray-text);
      }

      .no-data i {
        font-size: 48px;
        margin-bottom: 16px;
        display: block;
        opacity: 0.5;
      }

      .dashboard-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        padding: 30px;
      }

      @media (max-width: 1024px) {
        .dashboard-grid {
          grid-template-columns: 1fr;
        }
      }

      .chart-container {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow-md);
      }

      .chart-container h3 {
        margin-top: 0;
        color: var(--dark-text);
        font-weight: 700;
      }

      .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--gray-text);
      }

      .empty-state i {
        font-size: 48px;
        opacity: 0.3;
        margin-bottom: 16px;
        display: block;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <?php
      include '../layout/sidebar.php';

      // Hitung total buku
      $total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM buku"))['count'];

      // Hitung total anggota
      $total_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM anggota"))['count'];

      // Hitung buku yang sedang dipinjam
      $buku_dipinjam = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM peminjaman WHERE status != 'Dikembalikan'"))['count'];

      // Hitung buku terlambat
      $buku_terlambat = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM peminjaman WHERE tanggal_pengembalian < NOW() AND status != 'Dikembalikan'"))['count'];

      // Query untuk pengajuan peminjaman terbaru
      $pengajuan_terbaru = mysqli_query($conn, "SELECT * FROM pengajuan_peminjaman ORDER BY id_pengajuan_peminjaman DESC LIMIT 5");

      // Query untuk peminjaman terbaru
      $peminjaman_terbaru = mysqli_query($conn, "SELECT p.id_peminjaman, a.nama_anggota, b.judul_buku, p.tanggal_pinjam, p.tanggal_pengembalian, p.status FROM peminjaman p JOIN anggota a ON p.id_anggota = a.id_anggota JOIN buku b ON p.id_buku = b.id_buku ORDER BY p.id_peminjaman DESC LIMIT 5");
      ?>

      <main class="dashboard-content">
        <header>
          <h3><i class="fas fa-chart-line"></i> Dashboard Admin</h3>
        </header>

        <!-- Stats Cards -->
        <div class="stats-container">
          <div class="stat-card">
            <div class="stat-icon blue">
              <i class="fas fa-book"></i>
            </div>
            <div class="stat-content">
              <h3>Total Buku</h3>
              <p><?php echo $total_buku; ?></p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon green">
              <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
              <h3>Total Anggota</h3>
              <p><?php echo $total_anggota; ?></p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon orange">
              <i class="fa-solid fa-hand-holding-hand"></i>
            </div>
            <div class="stat-content">
              <h3>Buku Sedang Dipinjam</h3>
              <p><?php echo $buku_dipinjam; ?></p>
            </div>
          </div>

          <div class="stat-card">
            <div class="stat-icon red">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-content">
              <h3>Buku Terlambat</h3>
              <p><?php echo $buku_terlambat; ?></p>
            </div>
          </div>
        </div>

        <!-- Peminjaman Terbaru -->
        <div class="recent-section">
          <div class="section-header">
            <h2><i class="fas fa-list"></i> Peminjaman Terbaru</h2>
            <a href="peminjaman.php">Lihat Semua →</a>
          </div>

          <div class="recent-table">
            <table>
              <thead>
                <tr>
                  <th>Nama Anggota</th>
                  <th>Judul Buku</th>
                  <th>Tanggal Pinjam</th>
                  <th>Tanggal Kembali</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (mysqli_num_rows($peminjaman_terbaru) > 0) {
                  while ($row = mysqli_fetch_assoc($peminjaman_terbaru)) {
                    $status_class = 'status-' . strtolower(str_replace(' ', '', $row['status']));
                    echo "
                    <tr>
                      <td>{$row['nama_anggota']}</td>
                      <td>{$row['judul_buku']}</td>
                      <td>" . date('d/m/Y', strtotime($row['tanggal_pinjam'])) . "</td>
                      <td>" . date('d/m/Y', strtotime($row['tanggal_pengembalian'])) . "</td>
                      <td><span class='status-badge status-disetujui'>{$row['status']}</span></td>
                    </tr>
                    ";
                  }
                } else {
                  echo "<tr><td colspan='5' class='no-data'><i class='fas fa-book'></i> Tidak ada data peminjaman</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>

      </main>
    </div>
    <script src="../script.js"></script>
  </body>
</html>
