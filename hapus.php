<?php
include 'config.php';
include 'helpers.php';

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];

// Cek apakah data ada sebelum menghapus
$data = getStudentById($conn, $id);

if ($data) {
    // Data ada, lakukan penghapusan
    if (deleteStudent($conn, $id)) {
        redirect("index.php", "deleted");
    } else {
        redirect("index.php", "delete_error");
    }
} else {
    redirect("index.php", "notfound");
}
?>
