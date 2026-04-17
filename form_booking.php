<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Booking Servis</title>
    <link rel="stylesheet" href="stylee.css">
</head>

<body>
    <div class="nav">
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>

    <div class="container">
        <h2>Buat Antrean Baru</h2>
        <form action="aksi.php?proses=tambah_booking" method="POST">

            <div class="form-group">
                <label>Tanggal Rencana Servis</label>
                <input type="date" name="tanggal" required>
            </div>

            <div class="form-group">
                <label>Pilih Laptop</label>
                <select name="id_laptop" required>
                    <option value="">-- Pilih Laptop Anda --</option>
                    <?php
                    $uid = $_SESSION['id'];
                    $q_laptop = mysqli_query($koneksi, "SELECT * FROM laptop WHERE id_pemilik='$uid'");
                    while ($row = mysqli_fetch_assoc($q_laptop)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nama_laptop'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Jenis Layanan</label>
                <select name="id_servis" required>
                    <option value="">-- Pilih Layanan --</option>
                    <?php

                    $q_servis = mysqli_query($koneksi, "SELECT * FROM servis");
                    while ($row = mysqli_fetch_assoc($q_servis)) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nama_servis'] . " (Rp " . number_format($row['harga_servis'], 0, ',', '.') . ")</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Keluhan (Opsional)</label>
                <textarea name="keluhan" placeholder="Ceritakan masalah laptopnya..."></textarea>
            </div>

            <button type="submit" style="margin-top: 10px;">Daftar Sekarang</button>
        </form>
    </div>
</body>

</html>
