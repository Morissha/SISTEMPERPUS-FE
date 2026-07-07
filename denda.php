<?php 
include '../database/database.php';
session_start();

// Cek apakah user sudah login
if(!isset($_SESSION['nim']) || empty($_SESSION['nim'])) {
    header('Location: ../login.php');
    exit();
}

// Ambil denda anggota
$id_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_anggota FROM anggota WHERE nim = '".$_SESSION['nim']."'"))['id_anggota'];

$denda_query = @mysqli_query($conn, 
    "SELECT d.*, b.judul_buku FROM denda d 
     JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman 
     JOIN buku b ON p.id_buku = b.id_buku
     WHERE d.id_anggota = '$id_anggota' 
     ORDER BY d.tanggal_denda DESC");

if(!$denda_query) {
    $denda_query = mysqli_query($conn, "SELECT 1 WHERE 0"); // Empty result
}

// Total denda
$total_denda = @mysqli_fetch_assoc(mysqli_query($conn, 
    "SELECT COALESCE(SUM(jumlah_denda), 0) as total FROM denda 
     WHERE id_anggota = '$id_anggota'"))['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denda Saya</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>


        .info-card.paid {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .info-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .info-card .amount {
            font-size: 28px;
            font-weight: bold;
        }


        .denda-judul {
            font-weight: bold;
            color: #333;
            font-size: 16px;
        }
        .denda-item.paid .denda-amount {
            color: #28a745;
        }
        .denda-detail {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            font-size: 13px;
            color: #666;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-item label {
            font-weight: bold;
            color: #999;
            font-size: 11px;
        }
        .empty-message {
            text-align: center;
            padding: 40px 20px;
            color: #999;
        }
        .empty-message i {
            font-size: 48px;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
</head>
<body>

<div class="container">
    <?php include '../layout/sidebar.php';?>
    <main>
        <header>
          <h3>Denda Saya</h3>
        </header>

        <!-- Info Cards -->
        <div class="denda-info">
            <div class="info-card">
                <h3>Total Denda</h3>
                <div class="amount">Rp <?php echo number_format($total_denda, 0, ',', '.'); ?></div>
            </div>
        </div>

        <div class="table-section">
          <div class="table-header">
            <div class="tittle">
              <h3>Riwayat Denda</h3>
            </div>
          </div>

          <?php 
          if(mysqli_num_rows($denda_query) == 0): 
          ?>
          <div class="empty-message">
              <i class="fas fa-smile-wink"></i>
              <p>Tidak ada denda. Selamat! 🎉</p>
          </div>
          <?php 
          else: 
              while($row = mysqli_fetch_assoc($denda_query)): 
          ?>
          <div class="denda-item">
              <div class="denda-header">
                  <div class="denda-judul">
                      <?php echo $row['judul_buku']; ?>
                  </div>
                  <div class="denda-amount">
                      Rp <?php echo number_format($row['jumlah_denda'], 0, ',', '.'); ?>
                  </div>
              </div>
              <div class="denda-detail">
                  <div class="detail-item">
                      <label>Tanggal Denda</label>
                      <value><?php echo date('d/m/Y', strtotime($row['tanggal_denda'])); ?></value>
                  </div>
                  <div class="detail-item">
                      <label>Keterangan</label>
                      <value><?php echo $row['keterangan'] ?? '-'; ?></value>
                  </div>
              </div>
          </div>
          <?php 
              endwhile;
          endif; 
          ?>
        </div>

    </main>
</div>
</body>
</html>
