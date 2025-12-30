<?php
require_once __DIR__ . '/../config/app.php';
require_admin();
$title = 'Admin • ' . APP_NAME;
$active = 'admin';

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Dashboard Admin (CRUD)</h2>
  <p class="muted">Kelola seluruh konten website Desa Tanjung.</p>

  <div style="height:14px"></div>

  <div class="grid">
    <div class="card">
      <h4>Profil Desa (Halaman)</h4>
      <p class="muted">Kelola konten: Wilayah, Sejarah, Visi &amp; Misi, Peta Desa (iframe), Data Desa.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/pages/index.php') ?>">Kelola Halaman Profil</a>
    </div>

    <div class="card">
      <h4>Perangkat Desa</h4>
      <p class="muted">Kelola daftar perangkat desa beserta foto/jabatan.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/officials/index.php') ?>">Kelola Perangkat</a>
    </div>

    <div class="card">
      <h4>Berita</h4>
      <p class="muted">Kelola berita desa.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/posts/index.php?type=berita') ?>">Kelola Berita</a>
    </div>

    <div class="card">
      <h4>Pengumuman</h4>
      <p class="muted">Kelola pengumuman resmi.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/posts/index.php?type=pengumuman') ?>">Kelola Pengumuman</a>
    </div>

    <div class="card">
      <h4>Galeri</h4>
      <p class="muted">Tambah/hapus foto galeri.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/gallery/index.php') ?>">Kelola Galeri</a>
    </div>

    <div class="card">
      <h4>UMKM</h4>
      <p class="muted">Kelola data UMKM.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/umkm/index.php') ?>">Kelola UMKM</a>
    </div>

    <div class="card">
      <h4>Layanan</h4>
      <p class="muted">Kelola daftar layanan desa.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/services/index.php') ?>">Kelola Layanan</a>
    </div>

    <div class="card">
      <h4>Permohonan Layanan</h4>
      <p class="muted">Lihat dan ubah status permohonan layanan dari warga.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/requests/index.php') ?>">Kelola Permohonan</a>
    </div>

    <div class="card">
      <h4>User Warga</h4>
      <p class="muted">Kelola akun masyarakat (aktif/nonaktif).</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/users/index.php') ?>">Kelola User</a>
    </div>

    <div class="card">
      <h4>Pesan Kontak</h4>
      <p class="muted">Lihat pesan masuk dari halaman kontak.</p>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/admin/contacts/index.php') ?>">Lihat Pesan</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>