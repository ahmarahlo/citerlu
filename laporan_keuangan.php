<?php
session_start();
include 'koneksi.php';
if($_SESSION['role'] != 'Admin') header("Location: dashboard.php");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Laporan Keuangan Laba Rugi</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php">Kembali Dashboard</a>
    </div>
    <div class="container" style="max-width: 900px;">
        <h1 style="text-align: center; color: #2c3e50; margin-bottom: 30px;">Laporan Keuangan & Laba Rugi</h1>
        
        <?php
        // Hitung total jasa yang status Selesai
        $q_jasa = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(s.harga_servis) as total_jasa FROM booking b JOIN servis s ON b.id_servis = s.id WHERE b.status='Selesai'"));
        $tot_jasa = intval($q_jasa['total_jasa']);
        
        // Hitung total jual dan modal sparepart dari detail_servis untuk booking Selesai
        $q_part = mysqli_query($koneksi, "SELECT ds.qty, ds.harga_satuan, sp.harga_beli FROM detail_servis ds JOIN booking b ON ds.id_booking = b.id JOIN sparepart sp ON ds.id_sparepart = sp.id WHERE b.status='Selesai'");
        
        $tot_jual_part = 0;
        $tot_modal_part = 0;
        while($p = mysqli_fetch_assoc($q_part)) {
            $tot_jual_part += ($p['qty'] * $p['harga_satuan']);
            $tot_modal_part += ($p['qty'] * $p['harga_beli']);
        }
        
        $laba = ($tot_jual_part + $tot_jasa) - $tot_modal_part;
        ?>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div style="background: #e1f5fe; padding: 20px; border-radius: 8px; border-left: 5px solid #03a9f4;">
                <p style="margin: 0; font-size: 14px; color: #555;">Total Jasa Servis</p>
                <h3 style="margin: 5px 0; color: #0288d1;">Rp <?= number_format($tot_jasa, 0, ',', '.') ?></h3>
            </div>
            <div style="background: #e8f5e9; padding: 20px; border-radius: 8px; border-left: 5px solid #4caf50;">
                <p style="margin: 0; font-size: 14px; color: #555;">Penjualan Parts</p>
                <h3 style="margin: 5px 0; color: #2e7d32;">Rp <?= number_format($tot_jual_part, 0, ',', '.') ?></h3>
            </div>
            <div style="background: #fff3e0; padding: 20px; border-radius: 8px; border-left: 5px solid #ff9800;">
                <p style="margin: 0; font-size: 14px; color: #555;">Modal Terpakai</p>
                <h3 style="margin: 5px 0; color: #e65100;">Rp <?= number_format($tot_modal_part, 0, ',', '.') ?></h3>
            </div>
        </div>

        <div style="background: #2c3e50; color: white; padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <p style="margin: 0; font-size: 18px; opacity: 0.9;">LABA BERSIH SAAT INI</p>
            <h2 style="margin: 10px 0 0 0; font-size: 48px;">Rp <?= number_format($laba, 0, ',', '.') ?></h2>
        </div>

        <p style="margin-top: 20px; font-size: 12px; color: #777; font-style: italic; text-align: center;">* Laba dihitung dari (Jasa + Penjualan) - Modal Sparepart untuk semua servis berstatus 'Selesai'.</p>
    </div>
</body>
</html>
