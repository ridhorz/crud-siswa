<?php
include 'config.php';
include 'helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama          = trim($_POST['nama'] ?? '');
    $nis           = trim($_POST['nis'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $kelas         = trim($_POST['kelas'] ?? '');
    $jurusan       = trim($_POST['jurusan'] ?? '');

    $errors = validateStudentData($nama, $nis, $jenis_kelamin, $kelas, $jurusan);

    if (empty($errors) && checkNisExists($conn, $nis)) {
        $errors['nis'] = "NIS sudah digunakan. Silakan gunakan NIS lain.";
    }

    if (!empty($errors)) {
        // store per-field errors
        $_SESSION['field_errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
    } else {
        if (createStudent($conn, $nama, $nis, $jenis_kelamin, $kelas, $jurusan)) {
            redirect("index.php", "created");
        } else {
            $_SESSION['field_errors'] = ['general' => "Terjadi kesalahan: " . mysqli_error($conn)];
            $_SESSION['old_data'] = $_POST;
        }
    }
}

$old = $_SESSION['old_data'] ?? [];
$selectedJK = $old['jenis_kelamin'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Siswa</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body>

<div class="page-wrapper">

    <h1 class="title-main">Tambah Siswa</h1>
    <p class="title-sub">Isi form berikut untuk menambahkan data siswa baru.</p>

    <div class="form-card">
        <div class="form-inner">
            <?php $field_errors = $_SESSION['field_errors'] ?? []; ?>
            <?php if (!empty($field_errors) && isset($field_errors['general'])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($field_errors['general']) ?>
                </div>
            <?php endif; ?>

            <form id="formSiswa" action="" method="post" novalidate>
                <div class="form-group">
                    <label for="nama">Nama <span class="required">*</span></label>
                    <input
                        type="text"
                        name="nama"
                        id="nama"
                        value="<?= htmlspecialchars($old['nama'] ?? '') ?>"
                        maxlength="100"
                        placeholder="Masukkan nama lengkap"
                    >
                    <div class="input-hint" id="err-nama"><?= htmlspecialchars($field_errors['nama'] ?? '') ?></div>
                </div>

                <div class="form-group">
                    <label for="nis">NIS <span class="required">*</span></label>
                    <input
                        type="text"
                        name="nis"
                        id="nis"
                        value="<?= htmlspecialchars($old['nis'] ?? '') ?>"
                        maxlength="20"
                        placeholder="Masukkan NIS"
                    >
                    <div class="input-hint" id="err-nis"><?= htmlspecialchars($field_errors['nis'] ?? '') ?></div>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" <?= $selectedJK === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $selectedJK === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                    <div class="input-hint" id="err-jk"><?= htmlspecialchars($field_errors['jenis_kelamin'] ?? '') ?></div>
                </div>

                <div class="form-group">
                    <label for="kelas">Kelas <span class="required">*</span></label>
                    <input
                        type="text"
                        name="kelas"
                        id="kelas"
                        value="<?= htmlspecialchars($old['kelas'] ?? '') ?>"
                        placeholder="XII RPL 2"
                        maxlength="50"
                    >
                    <div class="input-hint" id="err-kelas"><?= htmlspecialchars($field_errors['kelas'] ?? '') ?></div>
                </div>

                <div class="form-group">
                    <label for="jurusan">Jurusan <span class="required">*</span></label>
                    <input
                        type="text"
                        name="jurusan"
                        id="jurusan"
                        value="<?= htmlspecialchars($old['jurusan'] ?? '') ?>"
                        placeholder="Rekayasa Perangkat Lunak"
                        maxlength="100"
                    >
                    <div class="input-hint" id="err-jurusan"><?= htmlspecialchars($field_errors['jurusan'] ?? '') ?></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <span class="btn-text">Simpan</span>
                        <span class="btn-loading" style="display: none;">Menyimpan...</span>
                    </button>
                    <a href="index.php" class="btn btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>

    <p class="footer-text">Idhoo RZ © <?= date('Y'); ?></p>
</div>
<?php unset($_SESSION['old_data'], $_SESSION['field_errors']); ?>
</body>
</html>
