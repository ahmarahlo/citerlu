<?php
session_start();
include 'koneksi.php';
if(!isset($_SESSION['id'])) header("Location: login.php");

$id_booking = isset($_GET['id_booking']) ? $_GET['id_booking'] : 0;

$q_nota = mysqli_query($koneksi, "SELECT n.*, b.tanggal as tgl_booking, l.nama_laptop, u.username, s.nama_servis, s.harga_servis 
                                  FROM nota_pembayaran n 
                                  JOIN booking b ON n.id_booking = b.id 
                                  JOIN laptop l ON b.id_laptop = l.id 
                                  JOIN users u ON l.id_pemilik = u.id 
                                  JOIN servis s ON b.id_servis = s.id 
                                  WHERE n.id_booking='$id_booking'");

if(mysqli_num_rows($q_nota) == 0) {
    die("Nota belum diterbitkan atau booking tidak valid.");
}

$nota = mysqli_fetch_assoc($q_nota);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Nota Pembayaran #<?= $nota['id'] ?></title>
    <style>
        .kotak-nota {
            border: 1px solid black;
            width: 400px;
            padding: 20px;
            margin: 20px auto;
            font-family: sans-serif;
            font-size: 14px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
<div class="kotak-nota">
    <h2>Nota Pembayaran TechFix</h2>
    <p>
        No. Nota: <b>#<?= $nota['id'] ?></b><br>
        Tanggal : <?= $nota['tanggal_selesai'] ?><br>
        Pelanggan: <?= $nota['username'] ?><br>
        Laptop  : <?= $nota['nama_laptop'] ?>
    </p>
    <hr>
    
    <table border="1" style="width: 100%; border-collapse: collapse;">
        <tr>
            <th>Daftar Layanan & Sparepart</th>
            <th>Subtotal</th>
        </tr>
        <tr>
            <td>[JASA] <?= $nota['nama_servis'] ?></td>
            <td>Rp <?= number_format($nota['harga_servis'],0,',','.') ?></td>
        </tr>
        <?php
        $q_part = mysqli_query($koneksi, "SELECT ds.*, sp.sku FROM detail_servis ds JOIN spareparts sp ON ds.id_sparepart = sp.id WHERE ds.id_booking='$id_booking'");
        while($part = mysqli_fetch_assoc($q_part)){
            echo "<tr>
                    <td>[PART] ".$part['sku']." (".$part['qty']."x)</td>
                    <td>Rp ".number_format($part['qty'] * $part['harga_satuan'],0,',','.')."</td>
                  </tr>";
        }
        ?>
        <tr>
            <th>TOTAL BAYAR</th>
            <th>Rp <?= number_format($nota['total_biaya'],0,',','.') ?></th>
        </tr>
    </table>
    
    <br>
    <p>
        <b>INFORMASI GARANSI:</b><br>
        - Garansi Servis (30 Hari): s/d <b><?= $nota['garansi_servis_habis'] ?></b><br>
        - Garansi Sparepart (1 Tahun): 
        <?php
        if($nota['garansi_sparepart_habis'] != '') {
            echo "s/d <b>".$nota['garansi_sparepart_habis']."</b>";
        } else {
            echo "<i>Tidak beli part</i>";
        }
        ?>
    </p>
    
    <br>
    <button onclick="window.print()">Cetak Nota</button>
</div>
</body>
</html>
