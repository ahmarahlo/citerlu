<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Pelanggan') {
    header("Location: dashboard.php");
    exit;
}

$id = $_GET['id'];
$user_id = $_SESSION['id'];
$q = mysqli_query($koneksi, "SELECT * FROM laptop WHERE id = '$id' AND id_pemilik = '$user_id'");
$data = mysqli_fetch_assoc($q);

if (!$data) {
    header("Location: form_laptop.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Laptop</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav">
        <a href="form_laptop.php">Kembali</a>
    </div>

    <div class="container">
        <h2>Edit Data Laptop</h2>
        <form action="aksi.php?proses=edit_laptop" method="POST">
            <input type="hidden" name="id" value="<?= $data['id'] ?>">
            <div class="form-group">
                <label>Nama/Merek Laptop</label>
                <input type="text" name="nama_laptop" value="<?= htmlspecialchars($data['nama_laptop']) ?>" required>
            </div>
            <div class="form-group">
                <label>Jenis/Tipe Laptop</label>
                <select name="jenis" required>
                    <option value="Gaming" <?= $data['jenis'] == 'Gaming' ? 'selected' : '' ?>>Gaming</option>
                    <option value="Office" <?= $data['jenis'] == 'Office' ? 'selected' : '' ?>>Office/Kuliah</option>
                    <option value="MacBook" <?= $data['jenis'] == 'MacBook' ? 'selected' : '' ?>>MacBook</option>
                    <option value="Lainnya" <?= $data['jenis'] == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>
            <button type="submit" class="btn">Update Laptop</button>
        </form>
    </div>
</body>
</html>
