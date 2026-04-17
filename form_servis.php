<?php
session_start();
include 'koneksi.php';

if ($_SESSION['role'] == 'Pelanggan') header("Location: dashboard.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data servis</title>
    <link rel="stylesheet" href="stylee.css">
</head>

<body>
    <div class="nav"><a href="dashboard.php">Kembali Dashboard</a></div>
    <div class="container">
        <h2>Tambah jasa servis baru </h2>
        <form action="aksi.php?proses=tambah_layanan" method="POST">
            <div class="form-group">
                <label>Nama Jasa/Servis</label>
                <input type="text" name="nama_servis" placeholder="Contoh: Ganti LCD" required>
            </div>
            <div class="form-group">
                <label>Biaya Jasa (Rp)</label>
                <input type="number" name="harga_servis" placeholder="Contoh: 150000" required>
            </div>
            <button type="submit" class="btn">Simpan Jasa</button>
        </form>

        <h2>Daftar Servis</h2>
        <table>
            <tr>
                <th>Nama Servis</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
            <?php
            $q = mysqli_query($koneksi, "SELECT * FROM servis");
            while ($s = mysqli_fetch_assoc($q)) {
                echo "<tr>
                        <td>" . $s['nama_servis'] . "</td>
                        <td>Rp " . number_format($s['harga_servis'], 0, ',', '.') . "</td>
                        <td>
                            <a href='edit_servis.php?id=" . $s['id'] . "' class='btn btn-success' style='background-color:#f39c12'>Edit</a>
                            <a href='aksi.php?proses=hapus_servis&id=" . $s['id'] . "' class='btn btn-danger' onclick='return confirm(\"Hapus Servis ini?\")'>Hapus</a>
                        </td>
                      </tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
