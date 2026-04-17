<?php
session_start();
include 'koneksi.php';

$proses = isset($_GET['proses']) ? $_GET['proses'] : '';


if ($proses == 'register') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        header("Location: register.php?msg=password_tidak_cocok");
        exit;
    }

    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username'");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: register.php?msg=username_sudah_ada");
        exit;
    }

    mysqli_query($koneksi, "INSERT INTO users (username, password, role) VALUES ('$username', '$password', 'Pelanggan')");
    header("Location: login.php?msg=register_sukses");
} elseif ($proses == 'tambah_laptop') {
    mysqli_query($koneksi, "INSERT INTO laptop (nama_laptop, jenis, id_pemilik) VALUES ('$_POST[nama_laptop]', '$_POST[jenis]', '$_SESSION[id]')");
    header("Location: form_laptop.php");
} elseif ($proses == 'edit_laptop') {
    mysqli_query($koneksi, "UPDATE laptop SET nama_laptop='$_POST[nama_laptop]', jenis='$_POST[jenis]' WHERE id='$_POST[id]' AND id_pemilik='$_SESSION[id]'");
    header("Location: form_laptop.php");
} elseif ($proses == 'hapus_laptop') {
    mysqli_query($koneksi, "DELETE FROM laptop WHERE id='$_GET[id]'");
    header("Location: form_laptop.php");
} elseif ($proses == 'tambah_sparepart') {
    $sku = $_POST['sku'];
    $nama_sparepart = isset($_POST['nama_sparepart']) ? $_POST['nama_sparepart'] : $sku;
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $jumlah = $_POST['jumlah'];
    
    mysqli_query($koneksi, "INSERT INTO sparepart (sku, nama_sparepart, jumlah, harga_beli, harga_jual) VALUES ('$sku', '$nama_sparepart', '$jumlah', '$harga_beli', '$harga_jual')");
    header("Location: form_sparepart.php");
} elseif ($proses == 'edit_sparepart') {
    $id = $_POST['id'];
    $sku = $_POST['sku'];
    $nama_sparepart = isset($_POST['nama_sparepart']) ? $_POST['nama_sparepart'] : $sku;
    $harga_beli = $_POST['harga_beli'];
    $harga_jual = $_POST['harga_jual'];
    $jumlah = isset($_POST['jumlah']) ? $_POST['jumlah'] : 0;

    mysqli_query($koneksi, "UPDATE sparepart SET sku='$sku', nama_sparepart='$nama_sparepart', jumlah='$jumlah', harga_beli='$harga_beli', harga_jual='$harga_jual' WHERE id='$id'");
    header("Location: form_sparepart.php");
} elseif ($proses == 'hapus_sparepart') {
    mysqli_query($koneksi, "DELETE FROM sparepart WHERE id='$_GET[id]'");
    header("Location: form_sparepart.php");
} elseif ($proses == 'tambah_booking') {
    $tgl = $_POST['tanggal'];
    $id_laptop = $_POST['id_laptop'];
    $id_servis = $_POST['id_servis'];
    $status = "Menunggu Antrean";

    $query = "INSERT INTO booking (tanggal, id_laptop, id_servis, status)
              VALUES ('$tgl', '$id_laptop', '$id_servis', '$status')";

    $simpan = mysqli_query($koneksi, $query);

    if ($simpan) {

        header("Location: dashboard.php");
    } else {

        echo "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
} elseif ($proses == 'hapus_servis') {
    mysqli_query($koneksi, "DELETE FROM servis WHERE id='$_GET[id]'");
    header("Location: form_servis.php");

} elseif ($proses == 'tambah_layanan') {
    $nama = $_POST['nama_servis'];
    $harga = $_POST['harga_servis'];

    $query = "INSERT INTO servis (nama_servis, harga_servis) VALUES ('$nama', '$harga')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: form_servis.php");
    } else {
        echo "Gagal nambah servis: " . mysqli_error($koneksi);
    }
} elseif ($proses == 'edit_layanan') {
    $id = $_POST['id'];
    $nama = $_POST['nama_servis'];
    $harga = $_POST['harga_servis'];
    mysqli_query($koneksi, "UPDATE servis SET nama_servis='$nama', harga_servis='$harga' WHERE id='$id'");
    header("Location: form_servis.php");
} elseif ($proses == 'update_status') {
    $id_booking = $_POST['id_booking'];
    $status_baru = $_POST['status'];

    mysqli_query($koneksi, "UPDATE booking SET status='$status_baru' WHERE id='$id_booking'");
    
    if($status_baru == 'Selesai') {
        $q_servis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT s.harga_servis FROM booking b JOIN servis s ON b.id_servis = s.id WHERE b.id='$id_booking'"));
        $total_biaya = $q_servis['harga_servis'];
        
        $q_sparepart = mysqli_query($koneksi, "SELECT SUM(qty * harga_satuan) as total_part FROM detail_servis WHERE id_booking='$id_booking'");
        $part = mysqli_fetch_assoc($q_sparepart);
        $total_biaya += intval($part['total_part']);
        
        $tgl_selesai = date('Y-m-d');
        $garansi_servis = date('Y-m-d', strtotime('+30 days'));
        
        $cek_part = mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM detail_servis WHERE id_booking='$id_booking'"));
        $garansi_part = ($cek_part > 0) ? "'" . date('Y-m-d', strtotime('+1 year')) . "'" : "NULL";
        
        $cek_nota = mysqli_query($koneksi, "SELECT id FROM nota_pembayaran WHERE id_booking='$id_booking'");
        if(mysqli_num_rows($cek_nota) == 0) {
            mysqli_query($koneksi, "INSERT INTO nota_pembayaran (id_booking, total_biaya, tanggal_selesai, garansi_servis_habis, garansi_sparepart_habis) VALUES ('$id_booking', '$total_biaya', '$tgl_selesai', '$garansi_servis', $garansi_part)");
        }
    }
    header("Location: dashboard.php");

} elseif ($proses == 'tambah_sparepart_ke_servis') {
    $id_booking = $_POST['id_booking'];
    $id_sparepart = $_POST['id_sparepart'];
    $qty = $_POST['qty'];

    $q_stok = mysqli_query($koneksi, "SELECT jumlah, harga_jual FROM spareparts WHERE id='$id_sparepart'");
    $stok_data = mysqli_fetch_assoc($q_stok);

    if ($stok_data['jumlah'] >= $qty) {
        $harga_satuan = $stok_data['harga_jual'];
        mysqli_query($koneksi, "UPDATE sparepart SET jumlah = jumlah - $qty WHERE id='$id_sparepart'");
        mysqli_query($koneksi, "INSERT INTO detail_servis (id_booking, id_sparepart, qty, harga_satuan) VALUES ('$id_booking', '$id_sparepart', '$qty', '$harga_satuan')");
    } else {
        mysqli_query($koneksi, "UPDATE booking SET status='Menunggu Sparepart' WHERE id='$id_booking'");
    }
    header("Location: dashboard.php");
}
