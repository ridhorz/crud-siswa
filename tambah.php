<?php
include 'config.php';
include 'helpers.php';

// Mulai session untuk error handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Handle submit form (tambah data)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama          = trim($_POST['nama'] ?? '');
    $nis           = trim($_POST['nis'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $kelas         = trim($_POST['kelas'] ?? '');
    $jurusan       = trim($_POST['jurusan'] ?? '');

    // Validasi
    $errors = validateStudentData($nama, $nis, $jenis_kelamin, $kelas, $jurusan);

    // Cek NIS unik
    if (empty($errors) && checkNisExists($conn, $nis)) {
        $errors[] = "NIS sudah digunakan. Silakan gunakan NIS lain.";
    }

    if (!empty($errors)) {
        // Simpan error & data lama ke session, tetap di halaman ini
        $_SESSION['errors']   = $errors;
        $_SESSION['old_data'] = $_POST;
    } else {
        // Simpan data siswa (INSERT)
        if (createStudent($conn, $nama, $nis, $jenis_kelamin, $kelas, $jurusan)) {
            redirect("index.php", "created");
        } else {
            $_SESSION['errors']   = ["Terjadi kesalahan: " . mysqli_error($conn)];
            $_SESSION['old_data'] = $_POST;
        }
    }
}

// Old data buat repopulate form
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
            <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
                <div class="alert alert-error">
                    <?php foreach ($_SESSION['errors'] as $error): ?>
                        <div><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php unset($_SESSION['errors']); ?>
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
                    <div class="input-hint" id="err-nama"></div>
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
                    <div class="input-hint" id="err-nis"></div>
                </div>

                <div class="form-group">
                    <label for="jenis_kelamin">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenis_kelamin" id="jenis_kelamin">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" <?= $selectedJK === 'Laki-laki' ? 'selected' : ''; ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $selectedJK === 'Perempuan' ? 'selected' : ''; ?>>Perempuan</option>
                    </select>
                    <div class="input-hint" id="err-jk"></div>
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
                    <div class="input-hint" id="err-kelas"></div>
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
                    <div class="input-hint" id="err-jurusan"></div>
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
<?php unset($_SESSION['old_data']); ?>
</body>
</html>
