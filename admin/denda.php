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

// Ambil semua denda
$query = mysqli_query($conn, 
    "SELECT d.*, a.nama_anggota, a.nim, b.judul_buku FROM denda d 
     JOIN anggota a ON d.id_anggota = a.id_anggota 
     JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman 
     JOIN buku b ON p.id_buku = b.id_buku
     ORDER BY d.tanggal_denda DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Denda</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        .alert {
            padding: 12px 20px;
            margin-bottom: 20px;
            border-radius: 4px;
            display: none;
        }
        .alert.show {
            display: block;
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
        .denda-amount {
            font-weight: bold;
            color: #dc3545;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-box h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .stat-box .number {
            font-size: 28px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include '../layout/sidebar.php';?>
    <main>
        <header>
          <h3>Daftar Denda</h3>
        </header>

        <?php if(isset($message)): ?>
        <div class="alert <?php echo $status == 'success' ? 'alert-success' : 'alert-error'; ?> show">
            <?php echo $message; ?>
        </div>
        <?php endif; ?>

        <!-- Statistik -->
        <div class="stats-grid">
            <div class="stat-box">
                <h3>Total Denda</h3>
                <div class="number">
                    <?php 
                    $total_denda = mysqli_fetch_assoc(mysqli_query($conn, 
                        "SELECT COALESCE(SUM(jumlah_denda), 0) as total FROM denda"))['total'];
                    echo "Rp " . number_format($total_denda, 0, ',', '.');
                    ?>
                </div>
            </div>
            <div class="stat-box warning">
                <h3>Total Anggota Berdenda</h3>
                <div class="number">
                    <?php 
                    $anggota_berdenda = mysqli_fetch_assoc(mysqli_query($conn, 
                        "SELECT COUNT(DISTINCT id_anggota) as total FROM denda"))['total'];
                    echo $anggota_berdenda;
                    ?>
                </div>
            </div>
            <div class="stat-box">
                <h3>Total Transaksi Denda</h3>
                <div class="number">
                    <?php 
                    $total_transaksi = mysqli_fetch_assoc(mysqli_query($conn, 
                        "SELECT COUNT(*) as total FROM denda"))['total'];
                    echo $total_transaksi;
                    ?>
                </div>
            </div>
        </div>

        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Daftar Denda per Anggota</h3>
            </div>
            <div class="toolbar">
              <div class="right">
                <input type="text" id="searchInput" placeholder="Cari Anggota..." />
              </div>
            </div>
          </div>

          <?php 
          // Kelompokkan denda per anggota
          $denda_per_anggota = [];
          $temp_query = mysqli_query($conn, 
              "SELECT d.*, a.nama_anggota, a.nim, b.judul_buku FROM denda d 
               JOIN anggota a ON d.id_anggota = a.id_anggota 
               JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman 
               JOIN buku b ON p.id_buku = b.id_buku
               ORDER BY a.nama_anggota ASC, d.tanggal_denda DESC");
          
          while($row = mysqli_fetch_assoc($temp_query)) {
              $id_anggota = $row['id_anggota'];
              if(!isset($denda_per_anggota[$id_anggota])) {
                  $denda_per_anggota[$id_anggota] = [
                      'nama_anggota' => $row['nama_anggota'],
                      'nim' => $row['nim'],
                      'denda_list' => []
                  ];
              }
              $denda_per_anggota[$id_anggota]['denda_list'][] = $row;
          }
          
          if(count($denda_per_anggota) == 0) {
              echo "<div style='text-align: center; padding: 40px;'>Tidak ada denda</div>";
          } else {
              foreach($denda_per_anggota as $id_anggota => $anggota_data):
                  $total_anggota = 0;
                  foreach($anggota_data['denda_list'] as $denda) {
                      $total_anggota += $denda['jumlah_denda'];
                  }
          ?>
          <div style="margin-bottom: 30px; border: 1px solid #ddd; border-radius: 8px; overflow: hidden;">
            <!-- Header Anggota -->
            <div class="denda-card" style=" color: white; padding: 15px 20px; display: flex; justify-content: space-between; align-items: center;">
              <div>
                <h4 style="margin: 0; font-size: 16px;"><?php echo $anggota_data['nama_anggota']; ?></h4>
                <p style="margin: 5px 0 0 0; font-size: 13px; opacity: 0.9;">NIM: <?php echo $anggota_data['nim']; ?></p>
              </div>
              <div style="text-align: right;">
                <p style="margin: 0; font-size: 12px; opacity: 0.9;">Total Denda</p>
                <p style="margin: 5px 0 0 0; font-size: 18px; font-weight: bold;">Rp <?php echo number_format($total_anggota, 0, ',', '.'); ?></p>
              </div>
            </div>

            <!-- Tabel Denda -->
            <table style="width: 100%; border-collapse: collapse;">
              <thead>
                <tr style="background-color: #f8f9fa; border-bottom: 2px solid #ddd;">
                  <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-right: 1px solid #ddd;">Judul Buku</th>
                  <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-right: 1px solid #ddd;">Jumlah Denda</th>
                  <th style="padding: 12px 15px; text-align: left; font-weight: 600; border-right: 1px solid #ddd;">Tanggal Denda</th>
                  <th style="padding: 12px 15px; text-align: left; font-weight: 600;">Keterangan</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($anggota_data['denda_list'] as $denda): ?>
                <tr style="border-bottom: 1px solid #eee;">
                  <td style="padding: 12px 15px; border-right: 1px solid #eee;"><?php echo $denda['judul_buku']; ?></td>
                  <td style="padding: 12px 15px; border-right: 1px solid #eee; color: #dc3545; font-weight: bold;">Rp <?php echo number_format($denda['jumlah_denda'], 0, ',', '.'); ?></td>
                  <td style="padding: 12px 15px; border-right: 1px solid #eee;"><?php echo date('d/m/Y', strtotime($denda['tanggal_denda'])); ?></td>
                  <td style="padding: 12px 15px;"><?php echo $denda['keterangan'] ?? '-'; ?></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php 
              endforeach;
          }
          ?>
        </div>
    </main>
</div>
<script src="../script.js"></script>
</body>
</html>
