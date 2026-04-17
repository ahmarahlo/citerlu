<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] == 'Pelanggan') {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$q = mysqli_query($koneksi, "SELECT * FROM servis WHERE id = '$id'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    header("Location: form_servis.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Jasa Servis</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav">
        <a href="form_servis.php">Kembali</a>
    </div>

    <div class="container">
        <h2>Edit Jasa Servis</h2>
        <form action="aksi.php?proses=edit_layanan" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <div class="form-group">
                <label>Nama Jasa/Servis</label>
                <input type="text" name="nama_servis" value="<?= htmlspecialchars($data['nama_servis']) ?>" required>
            </div>
            <div class="form-group">
                <label>Biaya Jasa (Rp)</label>
                <input type="number" name="harga_servis" value="<?= $data['harga_servis'] ?>" required>
            </div>
            <button type="submit" class="btn">Update Jasa</button>
        </form>
    </div>
</body>
</html>
