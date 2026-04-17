<?php
session_start();
include 'koneksi.php';
if(!isset($_SESSION['id'])) header("Location: login.php");
if($_SESSION['role'] == 'Pelanggan') header("Location: index.php");

$role = $_SESSION['role'];
$user_id = $_SESSION['id'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>TechFix Dashboard</title>
    <link rel="stylesheet" href="stylee.css">
</head>
<body>
    <div class="nav">
        <a href="dashboard.php">Dashboard</a>

        <?php if($role == 'Admin' || $role == 'Teknisi') { ?>
            <a href="form_sparepart.php">Stok Barang/Part</a>
        <?php } ?>
        <?php if($role == 'Admin') { ?>
            <a href="form_servis.php">Kelola Servis</a>
            <a href="laporan_keuangan.php">Laporan Keuangan</a>
        <?php } ?>
        
        <a href="logout.php" style="float: right;">Logout</a>
    </div>

    <div class="container">
        <h1>Dashboard - Role: <?= htmlspecialchars($role) ?></h1>
        <h2>Tabel Antrean Servis</h2>
        <table border="1" cellpadding="10" style="width:100%; border-collapse: collapse;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>ID</th>
                    <th>Tgl</th>
                    <th>Laptop</th>
                    <th>Servis (Jasa)</th>
                    <th>Sparepart Terpakai</th>
                    <th>Status</th>
                    <th>Total Biaya</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php
            
            $sql = "SELECT b.*, l.nama_laptop, s.nama_servis, s.harga_servis
                    FROM booking b
                    JOIN laptop l ON b.id_laptop = l.id
                    JOIN servis s ON b.id_servis = s.id";
            
            $sql .= " ORDER BY b.tanggal DESC, b.id ASC";

            $query = mysqli_query($koneksi, $sql);
            
            while($row = mysqli_fetch_assoc($query)){
                $id_booking = $row['id'];
                $jasa = (int)$row['harga_servis'];
                
                $q_detail = mysqli_query($koneksi, "SELECT ds.*, sp.sku FROM detail_servis ds JOIN spareparts sp ON ds.id_sparepart = sp.id WHERE ds.id_booking = '$id_booking'");
                $sparepart_list = [];
                $total_part = 0;
                while($ds = mysqli_fetch_assoc($q_detail)) {
                    $sparepart_list[] = $ds['qty'] . "x " . $ds['sku'] . " (Rp".number_format($ds['harga_satuan'],0,',','.').")";
                    $total_part += ($ds['qty'] * $ds['harga_satuan']);
                }
                $str_sparepart = (count($sparepart_list) > 0) ? implode("<br>", $sparepart_list) : "<i>(Tanpa Sparepart)</i>";

                $total_tagihan = $jasa + $total_part;

                echo "<tr>
                        <td>".$row['id']."</td>
                        <td>".$row['tanggal']."</td>
                        <td>".$row['nama_laptop']."</td>
                        <td>".$row['nama_servis']." <br><small>Rp".number_format($jasa,0,',','.')."</small></td>
                        <td>".$str_sparepart."</td>
                        <td><b>".$row['status']."</b></td>
                        <td><b>Rp ".number_format($total_tagihan, 0, ',', '.')."</b></td>
                        <td>";

                if(($role == 'Teknisi' || $role == 'Admin') && $row['status'] != 'Selesai') {
                    echo "<form action='aksi.php?proses=update_status' method='POST' style='margin-bottom: 5px;'>
                            <input type='hidden' name='id_booking' value='".$row['id']."'>
                            <select name='status' style='width:auto;'>
                                <option value='Menunggu Antrean' ".($row['status']=='Menunggu Antrean'?'selected':'').">Menunggu Antrean</option>
                                <option value='Dicek' ".($row['status']=='Dicek'?'selected':'').">Dicek</option>
                                <option value='Menunggu Sparepart' ".($row['status']=='Menunggu Sparepart'?'selected':'').">Menunggu Sparepart</option>
                                <option value='Dikerjakan' ".($row['status']=='Dikerjakan'?'selected':'').">Dikerjakan</option>
                                <option value='Selesai'>Selesai</option>
                            </select>
                            <button type='submit' class='btn' style='padding:4px 8px; font-size:12px;'>Update</button>
                          </form>";
                          
                    echo "<form action='aksi.php?proses=tambah_sparepart_ke_servis' method='POST' style='background:#f9f9f9; padding:5px; border:1px solid #ccc; border-radius:4px;'>
                            <input type='hidden' name='id_booking' value='".$row['id']."'>
                            <select name='id_sparepart' required style='width:auto; margin-bottom:5px;'>
                                <option value=''>-- Pilih Sparepart --</option>";
                            $q_sp = mysqli_query($koneksi, "SELECT * FROM spareparts WHERE jumlah > 0");
                            while($sp = mysqli_fetch_assoc($q_sp)) {
                                echo "<option value='".$sp['id']."'>".$sp['sku']." (Stok: ".$sp['jumlah'].")</option>";
                            }
                    echo "  </select><br>
                            <input type='number' name='qty' value='1' min='1' style='width:50px;' required>
                            <button type='submit' class='btn btn-success' style='padding:4px 8px; font-size:12px;'>+ Part</button>
                          </form>";
                } 
                
                if ($row['status'] == 'Selesai') {
                    echo "<a href='nota_pembayaran.php?id_booking=".$row['id']."' target='_blank' class='btn btn-success' style='font-size:12px;'>Cetak Nota</a>";
                }
                
                echo "  </td></tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
