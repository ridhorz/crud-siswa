<?php
include 'config.php';
include 'helpers.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id'])) {
    redirect("index.php");
}

$id = (int)$_GET['id'];
$data = getStudentById($conn, $id);

if (!$data) {
    redirect("index.php", "notfound");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama          = trim($_POST['nama'] ?? '');
    $nis           = trim($_POST['nis'] ?? '');
    $jenis_kelamin = trim($_POST['jenis_kelamin'] ?? '');
    $kelas         = trim($_POST['kelas'] ?? '');
    $jurusan       = trim($_POST['jurusan'] ?? '');

    $errors = validateStudentData($nama, $nis, $jenis_kelamin, $kelas, $jurusan);

    if (empty($errors) && checkNisExists($conn, $nis, $id)) {
        $errors[] = "NIS sudah digunakan. Silakan gunakan NIS lain.";
    }

    if (!empty($errors)) {
        $_SESSION['errors']   = $errors;
        $_SESSION['old_data'] = $_POST;
    } else {
        if (updateStudent($conn, $id, $nama, $nis, $jenis_kelamin, $kelas, $jurusan)) {
            redirect("index.php", "updated");
        } else {
            $_SESSION['errors']   = ["Terjadi kesalahan: " . mysqli_error($conn)];
            $_SESSION['old_data'] = $_POST;
        }
    }
}

$old = $_SESSION['old_data'] ?? [];
$selectedJK = $old['jenis_kelamin'] ?? ($data['jenis_kelamin'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Siswa</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="assets/style.css">
    <script src="assets/script.js" defer></script>
</head>
<body>

<div class="page-wrapper">

    <h1 class="title-main">Edit Siswa</h1>
    <p class="title-sub">Perbarui informasi siswa yang sudah terdaftar.</p>

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
                        value="<?= htmlspecialchars($old['nama'] ?? $data['nama']); ?>"
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
                        value="<?= htmlspecialchars($old['nis'] ?? $data['nis']); ?>"
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
                        value="<?= htmlspecialchars($old['kelas'] ?? $data['kelas']); ?>"
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
                        value="<?= htmlspecialchars($old['jurusan'] ?? $data['jurusan']); ?>"
                        maxlength="100"
                    >
                    <div class="input-hint" id="err-jurusan"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                        <span class="btn-text">Update</span>
                        <span class="btn-loading" style="display: none;">Memperbarui...</span>
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
