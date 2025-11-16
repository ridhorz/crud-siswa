<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "crud_siswa";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

function redirect($url, $msg = '') {
    if ($msg) {
        header("Location: $url?msg=$msg");
    } else {
        header("Location: $url");
    }
    exit;
}
?>