<?php
// =====================================================================
// TODO 1 - Memanggil file koneksi
//   koneksi.php = nama file yang berisi $conn (lihat daftar file di folder ini)
// =====================================================================
require 'koneksi.php';


$cari = '';

// =====================================================================
// TODO 2 - Membaca kata kunci pencarian dari URL
//   cari = nama parameter GET (nilai atribut name pada <input> pencarian)
// =====================================================================
if (isset($_GET['cari'])) {
    $cari = mysqli_real_escape_string($conn, $_GET['cari']);
}


// =====================================================================
// TODO 3 - Query daftar mobil + pencarian
//   showroom_mobil = nama tabel mobil
//   nama_mobil     = kolom nama mobil
//   brand_mobil    = kolom brand mobil
//   DESC           = urutan data terbaru di atas (berdasarkan id terbesar)
// =====================================================================
$hasil = mysqli_query($conn,
    "SELECT * FROM showroom_mobil
     WHERE nama_mobil LIKE '%$cari%' OR brand_mobil LIKE '%$cari%'
     ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>ShowRoom EAD</title>

    <!-- ===============================================================
         TODO 4 - Menghubungkan CSS
           style.css = nama file .css di folder ini
         =============================================================== -->
    <link rel="stylesheet" href="style.css">

</head>
<body><main>
<p class="label">PRAKTIKUM PAW / MODUL 2</p>
<h1>ShowRoom EAD</h1>
<p>Kelola data mobil yang tersedia di showroom.</p>

<!-- ===================================================================
     TODO 5 - Pesan sukses
       pesan = nama parameter yang dikirim setelah simpan/edit/hapus
     =================================================================== -->
<?php if (isset($_GET['pesan'])): ?>
    <p class="pesan">Perubahan data berhasil disimpan.</p>
<?php endif; ?>

<!-- ===================================================================
     TODO 6 - Tombol tambah
       tambah.php = nama file halaman form tambah mobil
     =================================================================== -->
<p><a class="tombol" href="tambah.php">Tambah mobil</a></p>

<!-- ===================================================================
     TODO 7 - Method form pencarian
       get = get atau post? (kata kunci harus terlihat di URL)
     =================================================================== -->
<form method="get" class="pencarian">
    <label for="cari">Cari nama atau brand</label>
    <input id="cari" name="cari" value="<?= $_GET['cari'] ?? '' ?>">
    <button>Cari</button> <a href="index.php">Reset</a>
</form>

<!-- ===================================================================
     TODO 8 - Jumlah hasil
       mysqli_num_rows = fungsi mysqli untuk menghitung jumlah baris
       0               = angka yang berarti "tidak ada data"
     =================================================================== -->
<p>Jumlah hasil: <?= mysqli_num_rows($hasil) ?></p>
<?php if (mysqli_num_rows($hasil) == 0): ?>
    <p>Data tidak ditemukan. Coba kata kunci lain.</p>
<?php endif; ?>

<div class="daftar">
<!-- ===================================================================
     TODO 9 - Perulangan data
       mysqli_fetch_assoc = fungsi mysqli mengambil satu baris array asosiatif
     =================================================================== -->
<?php while ($mobil = mysqli_fetch_assoc($hasil)): ?>
    <article>
        <!-- TODO 10 - isi key array dengan nama kolom di database.sql -->
        <p class="label"><?= $mobil['kode_mobil'] ?></p>
        <h2><?= $mobil['nama_mobil'] ?></h2>
        <p><?= $mobil['brand_mobil'] ?> &bull;
           <?= $mobil['tipe_mobil'] ?></p>
        <p>Warna: <?= $mobil['warna_mobil'] ?></p>

        <!-- TODO 11 - format Rupiah (contoh: Rp 310.000.000)
               0 = desimal
               , = pemisah desimal
               . = pemisah ribuan -->
        <p>Rp <?= number_format($mobil['harga_mobil'], 0, ',', '.') ?></p>

        <!-- TODO 12 - nama file tujuan tiap link -->
        <a href="detail.php?id=<?= $mobil['id'] ?>">Detail</a>
        <a href="edit.php?id=<?= $mobil['id'] ?>">Edit</a>
        <a href="hapus.php?id=<?= $mobil['id'] ?>">Hapus</a>
    </article>
<?php endwhile; ?>
</div>
</main></body>
</html>