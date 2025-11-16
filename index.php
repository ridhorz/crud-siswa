<?php
include 'config.php';
include 'helpers.php';

// Mulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ambil semua data siswa
$result = getAllStudents($conn);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- DataTables CSS (CDN) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

    <!-- CSS custom -->
    <link rel="stylesheet" href="assets/style.css">

    <!-- jQuery & DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

    <!-- JS custom -->
    <script src="assets/script.js" defer></script>

    <script>
        $(document).ready(function () {
            $('#tabelSiswa').DataTable({
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50, 100],
                language: {
                    lengthMenu: "Show _MENU_ entries",
                    search: "Search:",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    zeroRecords: "Tidak ada data yang cocok.",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: 6 } // kolom Aksi tidak bisa sort
                ]
            });
        });
    </script>
</head>
<body>

<!-- Main layout (sidebar and extra toggles removed) -->
<div class="page-wrapper">

    <h1 class="title-main">Data Siswa</h1>
    <p class="title-sub">CRUD sederhana untuk mengelola data siswa.</p>

    <div class="table-card">

        <div class="top-bar">
            <div class="top-bar-left">
                <span>Daftar seluruh data siswa</span>
            </div>
            <div class="top-bar-right">
                <a href="tambah.php" class="btn btn-primary">➕ Tambah Siswa</a>
            </div>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <?php if ($_GET['msg'] === 'created'): ?>
                <div class="alert alert-success" id="alertMessage">
                    ✅ Data siswa berhasil ditambahkan.
                </div>
            <?php elseif ($_GET['msg'] === 'updated'): ?>
                <div class="alert alert-success" id="alertMessage">
                    ✅ Data siswa berhasil diperbarui.
                </div>
            <?php elseif ($_GET['msg'] === 'deleted'): ?>
                <div class="alert alert-success" id="alertMessage">
                    ✅ Data siswa berhasil dihapus.
                </div>
            <?php elseif ($_GET['msg'] === 'notfound'): ?>
                <div class="alert alert-error" id="alertMessage">
                    ❌ Data tidak ditemukan.
                </div>
            <?php elseif ($_GET['msg'] === 'delete_error'): ?>
                <div class="alert alert-error" id="alertMessage">
                    ❌ Gagal menghapus data siswa.
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="table-responsive">
            <table id="tabelSiswa" class="display" style="width:100%">
                <thead>
                <tr>
                    <th style="width:60px;">No</th>
                    <th>Nama</th>
                    <th>NIS</th>
                    <th>Jenis Kelamin</th>
                    <th>Kelas</th>
                    <th>Jurusan</th>
                    <th style="width:160px;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['nis']); ?></td>
                        <td>
                            <?php if ($row['jenis_kelamin'] === 'Laki-laki'): ?>
                                <span class="badge badge-l">Laki-laki</span>
                            <?php else: ?>
                                <span class="badge badge-p">Perempuan</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['kelas']); ?></td>
                        <td><?= htmlspecialchars($row['jurusan']); ?></td>
                        <td class="cell-actions">
                            <a href="edit.php?id=<?= $row['id']; ?>" class="btn btn-edit">Edit</a>
                            <button type="button"
                                    class="btn btn-delete"
                                    onclick="confirmDelete(<?= $row['id']; ?>, '<?= htmlspecialchars($row['nama'], ENT_QUOTES); ?>')">
                                Hapus
                            </button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        

        <?php if (mysqli_num_rows(getAllStudents($conn)) == 0): ?>
            <div class="no-data-note">
                Belum ada data siswa. Silakan tambah data terlebih dahulu.
            </div>
        <?php endif; ?>

    </div>

    <p class="footer-text">Idhoo RZ © <?= date('Y'); ?></p>
</div>
</body>
</html>
