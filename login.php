<?php session_start(); if(isset($_SESSION['id'])) header("Location: dashboard.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 50px;">
        <h2>Login Sistem</h2>
        <?php if(isset($_GET['msg'])) {
            if($_GET['msg'] == 'register_sukses') echo "<p style='color:green;'>Register berhasil! Silakan login.</p>";
            else echo "<p style='color:red;'>Username atau Password salah!</p>";
        } ?>
       
        <form action="cek_login.php" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
        <p style="margin-top: 15px;"><a href="index.php">Kembali ke Halaman Utama</a> | <a href="register.php">Belum punya akun? Register</a></p>
    </div>
</body>
</html>
