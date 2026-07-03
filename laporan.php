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
$laporan_type = isset($_GET['type']) ? $_GET['type'] : 'peminjaman';

// Ambil data statistik umum
$total_buku = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM buku"))['total'];
$total_anggota = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM anggota"))['total'];
$total_peminjaman = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman"))['total'];
$peminjaman_aktif = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dipinjam'"))['total'];
$peminjaman_selesai = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dikembalikan'"))['total'];

// Data untuk laporan pengembalian
$total_dikembalikan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Dikembalikan'"))['total'];
$total_hilang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM peminjaman WHERE status = 'Hilang'"))['total'];

// Data untuk laporan denda
$total_denda_nominal = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(jumlah_denda), 0) as total FROM denda"))['total'];
$total_transaksi_denda = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM denda"))['total'];
$rata_denda = $total_transaksi_denda > 0 ? $total_denda_nominal / $total_transaksi_denda : 0;

// Data untuk laporan perpanjangan
$total_permohonan = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_peminjaman WHERE kategori = 'Perpanjangan'"))['total'];
$permohonan_disetujui = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_peminjaman WHERE kategori = 'Perpanjangan' AND status = 'Disetujui'"))['total'];
$permohonan_menunggu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pengajuan_peminjaman WHERE kategori = 'Perpanjangan' AND status = 'Menunggu'"))['total'];

// Query berdasarkan tipe laporan
if($laporan_type == 'peminjaman') {
    $query = mysqli_query($conn, 
        "SELECT p.*, a.nama_anggota, a.nim, b.judul_buku, k.nama_kategori 
         FROM peminjaman p 
         JOIN anggota a ON p.id_anggota = a.id_anggota 
         JOIN buku b ON p.id_buku = b.id_buku
         LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
         WHERE p.status = 'Dipinjam'
         ORDER BY p.tanggal_pinjam DESC");
    $table_columns = ['nama_anggota', 'judul_buku', 'kategori', 'tanggal_peminjaman', 'tanggal_pengembalian', 'status'];
} elseif($laporan_type == 'pengembalian') {
    $query = mysqli_query($conn, 
        "SELECT p.*, a.nama_anggota, a.nim, b.judul_buku, k.nama_kategori 
         FROM peminjaman p 
         JOIN anggota a ON p.id_anggota = a.id_anggota 
         JOIN buku b ON p.id_buku = b.id_buku
         LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
         WHERE p.status IN ('Dikembalikan', 'Hilang')
         ORDER BY p.tanggal_pengembalian DESC");
    $table_columns = ['nama_anggota', 'judul_buku', 'kategori', 'tanggal_pengembalian', 'status'];
} elseif($laporan_type == 'denda') {
    $query = mysqli_query($conn, 
        "SELECT d.*, a.nama_anggota, a.nim, b.judul_buku 
         FROM denda d 
         JOIN anggota a ON d.id_anggota = a.id_anggota 
         JOIN peminjaman p ON d.id_peminjaman = p.id_peminjaman
         JOIN buku b ON p.id_buku = b.id_buku
         ORDER BY d.tanggal_denda DESC");
    $table_columns = ['nama_anggota', 'judul_buku', 'jumlah_denda', 'tanggal_denda', 'keterangan'];
} else {
    $query = mysqli_query($conn, 
        "SELECT p.*, a.nama_anggota, a.nim, b.judul_buku, k.nama_kategori 
         FROM peminjaman p 
         JOIN anggota a ON p.id_anggota = a.id_anggota 
         JOIN buku b ON p.id_buku = b.id_buku
         LEFT JOIN kategori k ON b.id_kategori = k.id_kategori
         WHERE p.status = 'Diperpanjang'
         ORDER BY p.tanggal_pengembalian DESC");
    $table_columns = ['nama_anggota', 'judul_buku', 'kategori', 'tanggal_pengembalian', 'status'];
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="laporan.css">
</head>
<body>
<div class="container">
    <?php include '../layout/sidebar.php';?>
    <main>
        <div class="report-header">
    <h1 style="color:white !important; font-size:42px; opacity:1;">
        Laporan
    </h1>

    <p style="color:white !important; opacity:1;">
        Dashboard
    </p>
</div>

        <div style="padding: 0 30px;">
            <!-- Laporan Type Selector -->
            <div class="laporan-selector">
                <label for="reportType">Pilih Jenis Laporan:</label>
                <select id="reportType" onchange="window.location.href='?type=' + this.value">
                    <option value="peminjaman" <?php echo ($laporan_type == 'peminjaman') ? 'selected' : ''; ?>>
                        📚 Laporan Peminjaman
                    </option>
                    <option value="pengembalian" <?php echo ($laporan_type == 'pengembalian') ? 'selected' : ''; ?>>
                        📦 Laporan Pengembalian
                    </option>
                    <option value="denda" <?php echo ($laporan_type == 'denda') ? 'selected' : ''; ?>>
                        💰 Laporan Denda
                    </option>
                    <option value="perpanjangan" <?php echo ($laporan_type == 'perpanjangan') ? 'selected' : ''; ?>>
                        🔄 Laporan Perpanjangan
                    </option>
                </select>
            </div>

            <!-- Statistik berdasarkan tipe laporan -->
            <div class="stats-grid">
                <?php if($laporan_type == 'peminjaman'): ?>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $total_peminjaman; ?></div>
                        <div class="stat-label">Total Peminjaman</div>
                    </div>
                    <div class="stat-box active">
                        <div class="stat-number"><?php echo $peminjaman_aktif; ?></div>
                        <div class="stat-label">Sedang Dipinjam</div>
                    </div>
                    <div class="stat-box selesai">
                        <div class="stat-number"><?php echo $peminjaman_selesai; ?></div>
                        <div class="stat-label">Sudah Dikembalikan</div>
                    </div>

                <?php elseif($laporan_type == 'pengembalian'): ?>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo ($total_dikembalikan + $total_hilang); ?></div>
                        <div class="stat-label">Total Pengembalian</div>
                    </div>
                    <div class="stat-box selesai">
                        <div class="stat-number"><?php echo $total_dikembalikan; ?></div>
                        <div class="stat-label">Dikembalikan Lancar</div>
                    </div>
                    <div class="stat-box hilang">
                        <div class="stat-number"><?php echo $total_hilang; ?></div>
                        <div class="stat-label">Buku Hilang</div>
                    </div>

                <?php elseif($laporan_type == 'denda'): ?>
                    <div class="stat-box danger">
                        <div class="stat-number">Rp <?php echo number_format($total_denda_nominal, 0, ',', '.'); ?></div>
                        <div class="stat-label">Total Denda</div>
                        <div class="stat-sub"><?php echo $total_transaksi_denda; ?> Transaksi</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $total_transaksi_denda; ?></div>
                        <div class="stat-label">Jumlah Transaksi</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number">Rp <?php echo number_format($rata_denda, 0, ',', '.'); ?></div>
                        <div class="stat-label">Rata-rata Denda</div>
                        <div class="stat-sub">Per Transaksi</div>
                    </div>

                <?php elseif($laporan_type == 'perpanjangan'): ?>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $total_permohonan; ?></div>
                        <div class="stat-label">Total Permohonan</div>
                    </div>
                    <div class="stat-box selesai">
                        <div class="stat-number"><?php echo $permohonan_disetujui; ?></div>
                        <div class="stat-label">Disetujui</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-number"><?php echo $permohonan_menunggu; ?></div>
                        <div class="stat-label">Menunggu Verifikasi</div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Filter -->
            <div class="filter-wrapper">
                <input type="text" id="searchInput" placeholder="Cari data...">
                <button class="print-btn" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak Laporan
                </button>
            </div>

            <!-- Tabel Laporan -->
            <div class="report-table">
                <div class="table-title">
                    <i class="fas fa-table"></i> 
                    <?php 
                        if($laporan_type == 'peminjaman') echo 'Data Peminjaman Lengkap';
                        elseif($laporan_type == 'pengembalian') echo 'Data Pengembalian Lengkap';
                        elseif($laporan_type == 'denda') echo 'Data Denda Lengkap';
                        else echo 'Data Perpanjangan Lengkap';
                    ?>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Anggota</th>
                            <th>NIM</th>
                            <?php if($laporan_type == 'peminjaman'): ?>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Tgl Peminjaman</th>
                                <th>Tgl Pengembalian</th>
                                <th>Status</th>
                            <?php elseif($laporan_type == 'pengembalian'): ?>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Tgl Pengembalian</th>
                                <th>Status</th>
                            <?php elseif($laporan_type == 'denda'): ?>
                                <th>Judul Buku</th>
                                <th>Jumlah Denda</th>
                                <th>Tanggal Denda</th>
                                <th>Keterangan</th>
                            <?php else: ?>
                                <th>Judul Buku</th>
                                <th>Kategori</th>
                                <th>Tgl Pengembalian</th>
                                <th>Status</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($query)): 
                        ?>
                        <tr class="data-row" data-search="<?php echo strtolower($row['nama_anggota'] . ' ' . (isset($row['judul_buku']) ? $row['judul_buku'] : '')); ?>">
                            <td><?php echo $no; ?></td>
                            <td><?php echo $row['nama_anggota']; ?></td>
                            <td><?php echo $row['nim']; ?></td>
                            
                            <?php if($laporan_type == 'peminjaman'): ?>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td><?php echo $row['nama_kategori'] ?? '-'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pinjam'])); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengembalian'])); ?></td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    if($row['status'] == 'Dipinjam') $status_class = 'status-dipinjam';
                                    elseif($row['status'] == 'Dikembalikan') $status_class = 'status-dikembalikan';
                                    else $status_class = 'status-hilang';
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                                </td>
                            <?php elseif($laporan_type == 'pengembalian'): ?>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td><?php echo $row['nama_kategori'] ?? '-'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengembalian'])); ?></td>
                                <td>
                                    <?php $status_class = $row['status'] == 'Dikembalikan' ? 'status-dikembalikan' : 'status-hilang'; ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                                </td>
                            <?php elseif($laporan_type == 'denda'): ?>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td class="denda-amount">Rp <?php echo number_format($row['jumlah_denda'], 0, ',', '.'); ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_denda'])); ?></td>
                                <td><?php echo $row['keterangan'] ?? '-'; ?></td>
                            <?php else: ?>
                                <td><?php echo $row['judul_buku']; ?></td>
                                <td><?php echo $row['nama_kategori'] ?? '-'; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($row['tanggal_pengembalian'])); ?></td>
                                <td>
                                    <?php 
                                    $status_class = '';
                                    if($row['status'] == 'Dipinjam') $status_class = 'status-dipinjam';
                                    elseif($row['status'] == 'Dikembalikan') $status_class = 'status-dikembalikan';
                                    else $status_class = 'status-hilang';
                                    ?>
                                    <span class="status-badge <?php echo $status_class; ?>"><?php echo $row['status']; ?></span>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php 
                        $no++;
                        endwhile; 
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="laporan.js"></script>
<script src="../script.js"></script>
</body>
</html>