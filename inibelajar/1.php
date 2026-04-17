<?php
session_start();
include 'koneksi.php';
if(isset($_SESSION['id'])) header('location: login.php');

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - MeowGug</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <div>
    <a href="dashboard.php">Dashboard</a>
    <?php if($role == 'Pelanggan') {?>
    <a href="form_hewan.php">Data Hewan</a>
    <a href="form_booking.php">Data Hewan</a>
    <?php } ?>
    <?php if($role == 'Admin') {?>
    <a href="form_stok.php">Data Hewan</a>
    <?php } ?>
    <a href="logout.php">Logout</a>
  </div>

  <div>
    <h1>Dashboard Utama</h1>
    <p>Login Sebagai: <b><?= $role ?></b>
    <?php if($role == 'Pelanggan') echo ($_SESSION['is_member'] == 1) ? "[Status: Member Terdaftar" : " [Status : Pelanggan Biasa] "; ?>
  </p>
  <h2>Antrean & Pekerjaan</h2>
  <table>
    <thead>
      <tr>
        <th>No Antrean</th>
        <th>Tanggal</th>
        <th>Nama Hewan</th>
        <th>Layanan</th>
        <th>Status</th>
        <th>Total Tagihan</th>
        <th>Aksi</th>
      <tr>
    </thead>
    <tbody>
      <?php
      $sql = "SELECT b.*, h.nama_hewan, u.is_member, l.harga FROM booking b
                JOIN hewan h ON b.hewan_id = h.id 
                JOIN users u ON h.pemilik_id = u.id
                JOIN layanan l ON b.layanan_id = l.id
                LEFT JOIN stok s ON b.stok_id = s.id"
      
      ?>
    <tbody>
  </table>
  </div>
</body>
</html>