<?php
session_start();
include 'database/database.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Perpustakaan Digital</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Headbar -->
<header class="navbar">
    <div class="logo">📚 Perpustakaan</div>
    <a href="login.php" class="btn-login">Login</a>
</header>

<!-- Banner -->
<section class="banner">
    <div class="banner-overlay">
        <h1>Selamat Datang di Perpustakaan Digital</h1>
        <p>Temukan berbagai koleksi buku terbaik untuk menunjang pembelajaran</p>
    </div>
</section>

<!-- Info Perpustakaan -->
<section class="info">
    <h2>Tentang Perpustakaan</h2>
    <p>
        Perpustakaan ini menyediakan koleksi buku akademik dan non-akademik
        yang dapat dipinjam oleh anggota secara mudah dan cepat.
    </p>
</section>

<section class="toolbar">
    <form method="GET" action="index.php">
        <input type="text" name="search" placeholder="Cari judul atau penulis..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
        <select name="id_kategori">
            <option value="">Semua Kategori</option>
            <?php
            $queryKat = "SELECT id_kategori, nama_kategori FROM kategori ORDER BY nama_kategori";
            $resultKat = mysqli_query($conn, $queryKat);
            if ($resultKat) {
                while ($kat = mysqli_fetch_assoc($resultKat)) {
                    $selected = (isset($_GET['id_kategori']) && $_GET['id_kategori'] == $kat['id_kategori']) ? 'selected' : '';
                    echo '<option value="' . htmlspecialchars($kat['id_kategori']) . '" ' . $selected . '>' . htmlspecialchars($kat['nama_kategori']) . '</option>';
                }
            }
            ?>
        </select>
        <button type="submit">Cari</button>
    </form>
</section>

<!-- Daftar Buku -->
<section class="book-list">
    <?php
    $query = "SELECT * FROM buku WHERE 1=1";
    
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = mysqli_real_escape_string($conn, $_GET['search']);
        $query .= " AND (judul_buku LIKE '%$search%' OR pengarang LIKE '%$search%')";
    }
    
    if (isset($_GET['id_kategori']) && !empty($_GET['id_kategori'])) {
        $id_kategori = mysqli_real_escape_string($conn, $_GET['id_kategori']);
        $query .= " AND id_kategori = '$id_kategori'";
    }
    
    $result = mysqli_query($conn, $query);
    
    // Debug: tampilkan error jika ada
    if (!$result) {
        echo '<p style="color: red;">Error: ' . mysqli_error($conn) . '</p>';
    } elseif (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $stok = $row['stok'];
            $stokClass = $stok > 0 ? '' : 'habis';
    ?>
    <div class="book-card">
        <img src="<?php echo !empty($row['foto']) ? 'img/' . htmlspecialchars($row['foto']) : 'img/cover.jpg'; ?>" alt="Cover <?php echo htmlspecialchars($row['judul_buku']); ?>">
        <h3><?php echo htmlspecialchars($row['judul_buku']); ?></h3>
        <p>Penulis: <?php echo htmlspecialchars($row['pengarang']); ?></p>
        <p>Penerbit: <?php echo htmlspecialchars($row['penerbit']); ?></p>
        <p>Tahun: <?php echo htmlspecialchars($row['tahun']); ?></p>
        <span class="stok <?php echo $stokClass; ?>">Stok: <?php echo $stok; ?></span>
    </div>
    <?php 
        }
    } else {
        echo '<p>Belum ada buku dalam database.</p>';
    }
    ?>
</section>

<footer class="footer">
    <p>© 2026 Perpustakaan Digital</p>
</footer>

</body>
</html>
