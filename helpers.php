<?php
// Validasi input siswa
function validateStudentData($nama, $nis, $jenis_kelamin, $kelas, $jurusan) {
    $errors = [];
    
    if (empty($nama) || strlen($nama) < 2 || strlen($nama) > 100) {
        $errors[] = "Nama harus diisi (2-100 karakter).";
    }
    
    if (empty($nis) || !preg_match('/^[0-9]+$/', $nis) || strlen($nis) > 20) {
        $errors[] = "NIS harus diisi dengan angka (maksimal 20 digit).";
    }
    
    if (empty($jenis_kelamin) || !in_array($jenis_kelamin, ['Laki-laki', 'Perempuan'])) {
        $errors[] = "Jenis kelamin harus dipilih.";
    }
    
    if (empty($kelas) || strlen($kelas) > 50) {
        $errors[] = "Kelas harus diisi (maksimal 50 karakter).";
    }
    
    if (empty($jurusan) || strlen($jurusan) > 100) {
        $errors[] = "Jurusan harus diisi (maksimal 100 karakter).";
    }
    
    return $errors;
}

// Cek apakah NIS sudah ada di database
function checkNisExists($conn, $nis, $excludeId = null) {
    $query = "SELECT id FROM siswa WHERE nis = ?";
    $params = [$nis];
    $types = "s";
    
    if ($excludeId !== null) {
        $query .= " AND id != ?";
        $params[] = $excludeId;
        $types .= "i";
    }
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    
    return mysqli_num_rows($result) > 0;
}

// Ambil data siswa berdasarkan ID
function getStudentById($conn, $id) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM siswa WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    
    return $data;
}

// Simpan data siswa baru
function createStudent($conn, $nama, $nis, $jenis_kelamin, $kelas, $jurusan) {
    $stmt = mysqli_prepare($conn, "INSERT INTO siswa (nama, nis, jenis_kelamin, kelas, jurusan) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $nama, $nis, $jenis_kelamin, $kelas, $jurusan);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $success;
}

// Update data siswa
function updateStudent($conn, $id, $nama, $nis, $jenis_kelamin, $kelas, $jurusan) {
    $stmt = mysqli_prepare($conn, "UPDATE siswa SET nama=?, nis=?, jenis_kelamin=?, kelas=?, jurusan=? WHERE id=?");
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $nis, $jenis_kelamin, $kelas, $jurusan, $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $success;
}

// Hapus data siswa
function deleteStudent($conn, $id) {
    $stmt = mysqli_prepare($conn, "DELETE FROM siswa WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    return $success;
}

// Ambil semua siswa
function getAllStudents($conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM siswa ORDER BY id DESC");
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
    
    return $result;
}
