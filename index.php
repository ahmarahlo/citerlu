<?php
session_start();
include 'koneksi.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>TechFix & Parts</title>
    <link rel="stylesheet" href="stylee.css">
    <style>
        .hero {
            background-color: #2c3e50;
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .hero h1 {
            color: white;
            margin-top: 0;
            font-size: 28px;
        }
        .hero p {
            font-size: 18px;
            color: #ecf0f1;
        }
    </style>
</head>
<body>
    <div class="navigation">
        <div class="navleft">
            <p>TechFix</p>
        </div>
        <div class="navright">
            <a href="index.php">Home</a>
            <?php if(isset($_SESSION['id'])) { 
                if($_SESSION['role'] == 'Pelanggan') { ?>
                    <a href="form_laptop.php">Data Laptop</a>
                    <a href="form_booking.php">Booking Servis</a>
                <?php } else { ?>
                    <a href="dashboard.php">Dashboard</a>
                <?php } ?>
                <a href="logout.php">Logout</a>
            <?php } else { ?>
                <a href="login.php">Login / Masuk</a>
                <a href="register.php">Register</a>
            <?php } ?>
        </div>
    </div>
    
    <div class="container">
        <div class="hero">
            <h1>Selamat Datang di TechFix & Parts</h1>
            <p>Solusi Terbaik, Cepat, dan Terpercaya untuk Perawatan & Perbaikan Laptop Anda.</p>
        </div>
        
        <?php if(isset($_SESSION['id']) && $_SESSION['role'] == 'Pelanggan') { ?>
        <h2 style="margin-top:20px;">Lacak Servis Anda</h2>
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse; margin-bottom: 40px;">
            <tr style="background-color: #f2f2f2;">
                <th>Tgl</th>
                <th>Laptop</th>
                <th>Servis & Part</th>
                <th>Status</th>
                <th>Total Tagihan</th>
                <th>Nota</th>
            </tr>
            <?php
            $user_id = $_SESSION['id'];
            $sql = "SELECT b.*, l.nama_laptop, s.nama_servis, s.harga_servis
                    FROM booking b
                    JOIN laptop l ON b.id_laptop = l.id
                    JOIN servis s ON b.id_servis = s.id
                    WHERE l.id_pemilik = '$user_id' ORDER BY b.tanggal DESC, b.id ASC";
            $query = mysqli_query($koneksi, $sql);
            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)){
                    $id_booking = $row['id'];
                    $jasa = (int)$row['harga_servis'];
                    
                    $q_detail = mysqli_query($koneksi, "SELECT ds.*, sp.sku FROM detail_servis ds JOIN spareparts sp ON ds.id_sparepart = sp.id WHERE ds.id_booking = '$id_booking'");
                    $sparepart_list = [];
                    $total_part = 0;
                    while($ds = mysqli_fetch_assoc($q_detail)) {
                        $sparepart_list[] = $ds['qty'] . "x " . $ds['sku'];
                        $total_part += ($ds['qty'] * $ds['harga_satuan']);
                    }
                    $str_sparepart = (count($sparepart_list) > 0) ? implode("<br>", $sparepart_list) : "<i>(Tanpa Sparepart)</i>";

                    $total_tagihan = $jasa + $total_part;

                    echo "<tr>
                            <td>".$row['tanggal']."</td>
                            <td>".$row['nama_laptop']."</td>
                            <td>".$row['nama_servis']."<br><small>".$str_sparepart."</small></td>
                            <td><b>".$row['status']."</b></td>
                            <td><b>Rp ".number_format($total_tagihan, 0, ',', '.')."</b></td>
                            <td>";
                    if ($row['status'] == 'Selesai') {
                        echo "<a href='nota_pembayaran.php?id_booking=".$row['id']."' target='_blank' class='btn btn-success' style='font-size:12px; padding: 4px 8px;'>Cetak</a>";
                    } else {
                        echo "-";
                    }
                    echo "</td></tr>";
                }
            } else {
                echo "<tr><td colspan='6' style='text-align:center;'>Belum ada riwayat servis.</td></tr>";
            }
            ?>
        </table>
        <?php } ?>
        
        <h2>Daftar Harga Layanan Servis</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Servis</th>
                <th>Harga Jasa</th>
            </tr>
            <?php
            $no = 1;
            $q = mysqli_query($koneksi, "SELECT * FROM servis");
            if(mysqli_num_rows($q) > 0) {
                while ($row = mysqli_fetch_assoc($q)) {
                    echo "<tr>
                            <td>".$no++."</td>
                            <td>".$row['nama_servis']."</td>
                            <td>Rp ".number_format($row['harga_servis'],0,',','.')."</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='3' style='text-align:center;'>Belum ada data servis tersedia</td></tr>";
            }
            ?>
        </table>

        <h2 style="margin-top:40px;">Beli Sparepart Tambahan</h2>
        <table>
            <tr>
                <th>No</th>
                <th>Nama Sparepart</th>
                <th>Harga</th>
                <th>Stok Tersedia</th>
            </tr>
            <?php
            $no = 1;
            $q_sparepart = mysqli_query($koneksi, "SELECT * FROM spareparts WHERE jumlah > 0");
            if(mysqli_num_rows($q_sparepart) > 0) {
                while ($sp = mysqli_fetch_assoc($q_sparepart)) {
                    echo "<tr>
                            <td>".$no++."</td>
                            <td>".$sp['nama_sparepart']." (".$sp['sku'].")</td>
                            <td>Rp ".number_format($sp['harga_jual'],0,',','.')."</td>
                            <td>".$sp['jumlah']." Unit</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4' style='text-align:center;'>Stok sparepart sedang kosong</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
