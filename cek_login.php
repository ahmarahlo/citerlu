<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

$query = mysqli_query($koneksi, "SELECT * FROM users WHERE username='$username' AND password='$password'");
$cek = mysqli_num_rows($query);

if($cek > 0){
    $data = mysqli_fetch_assoc($query);
    $_SESSION['id'] = $data['id'];
    $_SESSION['role'] = $data['role'];
    
    if($data['role'] == 'Pelanggan') {
        header("Location: index.php");
    } else {
        header("Location: dashboard.php");
    }
} else {
    header("Location: login.php?msg=gagal");
}
?>
