<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "crud_siswa";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Set charset untuk mencegah encoding issues
mysqli_set_charset($conn, "utf8mb4");

// Helper function untuk redirect dengan pesan
function redirect($url, $msg = '') {
    if ($msg) {
        header("Location: $url?msg=$msg");
    } else {
        header("Location: $url");
    }
    exit;
}
?>