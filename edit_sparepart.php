<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] != 'Admin') header("Location: dashboard.php");

$id = $_GET['id'];
$data = mysqli_query($koneksi, "SELECT * FROM sparepart WHERE id='$id'");
$s = mysqli_fetch_assoc($data);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Sparepart</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav"><a href="form_sparepart.php">Batal</a></div>
    <div class="container">
        <h2>Edit Data Sparepart</h2>
        <form action="aksi.php?proses=edit_sparepart" method="POST">
            <input type="hidden" name="id" value="<?= $s['id'] ?>">
           
            <div class="form-group">
                <label>SKU</label>
                <input type="text" name="sku" value="<?= $s['sku'] ?>" required>
            </div>
            <div class="form-group">
                <label>Nama Sparepart</label>
                <input type="text" name="nama_sparepart" value="<?= $s['nama_sparepart'] ?>" required>
            </div>
            <div class="form-group">
                <label>Jumlah</label>
                <input type="number" name="jumlah" value="<?= $s['jumlah'] ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Beli</label>
                <input type="number" name="harga_beli" value="<?= $s['harga_beli'] ?>" required>
            </div>
            <div class="form-group">
                <label>Harga Jual</label>
                <input type="number" name="harga_jual" value="<?= $s['harga_jual'] ?>" required>
            </div>
            <button type="submit" class="btn">Update Data</button>
        </form>
    </div>
</body>
</html>
