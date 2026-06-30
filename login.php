<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
  </head>
  <body>
    <div class="login-container">
      <div class="logo">
        <img src="img/logo.png" alt="logo">
        <p>Sistem Informasi Peminjaman Buku Perpustakaan</p>
      </div>
      <div class="form-login">
        <h2>Login</h2>
        <form action="cek_login.php" method="POST">
          <label for="Username">Username: </label>
          <input type="text" name="username" required />
          <label for="Password">Password: </label>
          <input type="password" name="password" required />
          <div class="forgot">
            <p><a href="#">Lupa Kata Sandi</a></p>
          </div>
          <button class="btn btn-tambah" type="submit" name="login">Login</button>
          <p>Belum Punya akun? <a href="daftar.php">Daftar</a></p>
        </form>
      </div>
    </div>
  </body>
</html>
