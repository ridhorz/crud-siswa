# CRUD Siswa

<img width="1535" height="665" alt="Screenshot 2025-12-05 182621" src="https://github.com/user-attachments/assets/c79ccaaa-6259-4252-adf6-5987bb5e6397" />

Aplikasi CRUD sederhana untuk mengelola data siswa menggunakan bahasa pemrograman (PHP & MySQL). Aplikasi ini memungkinkan Anda menambahkan, melihat, mengedit, dan menghapus data siswa:
- Nama
- NIS
- Jenis Kelamin
- Kelas
- Jurusan


## Fitur Utama

- CRUD lengkap untuk tabel siswa
- Validasi real-time di form (client-side)
- Validasi server-side dengan pesan error per field
- Tabel menggunakan DataTables (search, sort, pagination)
- Modal konfirmasi sebelum hapus data

## Persyaratan

- PHP 7.4+ (dianjurkan PHP 8)
- MySQL / MariaDB
- Web server lokal (Laragon, XAMPP, MAMP)
- Browser (Google Chrome, Brave)

## Cara Instalasi

1. Salin folder ke folder web server (cth. **c:\laragon\www\crud-siswa**).
2. Buat database MySQL bernama `crud_siswa` dan import tabel siswa (atau jalankan skrip SQL Anda).
3. Sesuaikan koneksi database di config.php jika perlu.
4. Buka http://localhost/crud-siswa/index.php di browser.
