<?php
session_start();
include 'koneksi.php';
if ($_SESSION['role'] == 'Pelanggan') header("Location: index.php");
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Man  age Sparepart</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav"><a href="dashboard.php">Kembali Dashboard</a></div>
    <div class="container">
        <?php if($role == 'Admin') { ?>
        <h2>Input Sparepart Baru</h2>
        <form action="aksi.php?proses=tambah_sparepart" method="POST">
            <div class="form-group"><label>SKU</label><input type="text" name="sku" required></div>
            <div class="form-group"><label>Nama Sparepart</label><input type="text" name="nama_sparepart" required></div>
            <div class="form-group"><label>Harga Beli (Modal)</label><input type="number" name="harga_beli" required></div>
            <div class="form-group"><label>Harga Jual</label><input type="number" name="harga_jual" required></div>
            <div class="form-group"><label>Jumlah</label><input type="number" name="jumlah" required></div>
            <button type="submit" class="btn">Simpan</button>
        </form>
        <?php } ?>

        <h2>Data Sparepart</h2>
        <table border="1" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background:#eee;">
                    <th>ID</th>
                    <th>SKU</th>
                    <th>Nama Sparepart</th>
                    <?php if($role == 'Admin') { ?>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <?php } ?>
                    <th>Jumlah</th>
                    <?php if($role == 'Admin') { ?>
                    <th>Aksi</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            <?php
            $q = mysqli_query($koneksi, "SELECT * FROM sparepart");
            while ($s = mysqli_fetch_assoc($q)) {
                echo "<tr>
                        <td>" . $s['id'] . "</td>
                        <td>" . $s['sku'] . "</td>
                        <td>" . $s['nama_sparepart'] . "</td>";
                        
                if($role == 'Admin') {
                    echo "<td>Rp " . number_format($s['harga_beli'], 0, ',', '.') . "</td>";
                    echo "<td>Rp " . number_format($s['harga_jual'], 0, ',', '.') . "</td>";
                }
                
                echo "<td>" . $s['jumlah'] . "</td>";
                
                if($role == 'Admin') {
                    echo "<td>
                            <a href='edit_sparepart.php?id=" . $s['id'] . "' class='btn'>Edit</a>
                            <a href='aksi.php?proses=hapus_sparepart&id=" . $s['id'] . "' class='btn btn-danger' onclick='return confirm(\"Hapus sparepart ini?\")'>Hapus</a>
                          </td>";
                }
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
