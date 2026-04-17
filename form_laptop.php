<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['id']) || $_SESSION['role'] != 'Pelanggan') {
    header("Location: dashboard.php");
    exit;
}

$user_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html>

<head>
    <title>Data Laptop</title>
    <link rel="stylesheet" href="stylee.css">
</head>

<body>
    <div class="nav">
        <a href="dashboard.php">Kembali ke Dashboard</a>
    </div>

    <div class="container">
        <h2>Tambah Data Laptop</h2>
        <form action="aksi.php?proses=tambah_laptop" method="POST">
            <div class="form-group">
                <label>Nama/Merek Laptop</label>
                <input type="text" name="nama_laptop" placeholder="Contoh: Asus ROG Strix / Lenovo ThinkPad" required>
            </div>
            <div class="form-group">
                <label>Jenis/Tipe Laptop</label>
                <select name="jenis" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="Gaming">Gaming</option>
                    <option value="Office">Office/Kuliah</option>
                    <option value="MacBook">MacBook</option>
                    <option value="Lainnya">Lainnya</option>
                </select>
            </div>
            <button type="submit" class="btn">Simpan Laptop</button>
        </form>

        <h2>Daftar Laptop Saya</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Laptop</th>
                <th>Jenis</th>
                <th>Aksi</th>
            </tr>
            <?php
            $no = 1;
            $q = mysqli_query($koneksi, "SELECT * FROM laptop WHERE id_pemilik = '$user_id'");
            
            if (mysqli_num_rows($q) > 0) {
                while ($l = mysqli_fetch_assoc($q)) {
                    echo "<tr>
                            <td>" . $no++ . "</td>
                            <td>" . htmlspecialchars($l['nama_laptop']) . "</td>
                            <td>" . htmlspecialchars($l['jenis']) . "</td>
                            <td>
                                <a href='edit_laptop.php?id=" . $l['id'] . "' class='btn btn-success' style='background-color:#f39c12'>Edit</a>
                                <a href='aksi.php?proses=hapus_laptop&id=" . $l['id'] . "' class='btn btn-danger' onclick='return confirm(\"Hapus laptop ini dari data Anda?\")'>Hapus</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Belum ada data laptop. Silakan tambahkan terlebih dahulu!</td></tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>
