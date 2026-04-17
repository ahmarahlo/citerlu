<?php session_start(); if(isset($_SESSION['id'])) header("Location: dashboard.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 50px;">
        <h2>Register Akun Baru</h2>
        <?php if(isset($_GET['msg'])) echo "<p style='color:red;'>Username sudah ada atau password tidak cocok!</p>"; ?>
       
        <form action="aksi.php?proses=register" method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
        <p style="margin-top: 15px;"><a href="login.php">Sudah punya akun? Login</a></p>
    </div>
</body>
</html>