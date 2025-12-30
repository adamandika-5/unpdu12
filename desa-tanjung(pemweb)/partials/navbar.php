<?php $active = $active ?? ''; ?>

<div class="navbar">
  <a class="logo" href="<?= url('/index.php') ?>">
    <img src="<?= url('/assets/img/logo.png') ?>" alt="Logo Desa">
  </a>

  <ul class="nav">
    <li class="<?= $active === 'home' ? 'active' : '' ?>">
      <a href="<?= url('/index.php') ?>">Beranda</a>
    </li>

    <li class="dropdown <?= $active === 'profil' ? 'active' : '' ?>">
      <details>
        <summary>Profil Desa ▾</summary>
        <ul class="dropdown-menu">
          <li><a href="<?= url('/pages/profil/sejarah.php') ?>">Sejarah</a></li>
          <li><a href="<?= url('/pages/profil/visi_misi.php') ?>">Visi &amp; Misi</a></li>
          <li><a href="<?= url('/pages/profil/perangkat_desa.php') ?>">Perangkat Desa</a></li>
          <li><a href="<?= url('/pages/profil/peta_desa.php') ?>">Peta Desa</a></li>
          <li><a href="<?= url('/pages/profil/data_desa.php') ?>">Data Desa</a></li>
        </ul>
      </details>
    </li>

    <li class="dropdown <?= $active === 'informasi' ? 'active' : '' ?>">
      <details>
        <summary>Informasi ▾</summary>
        <ul class="dropdown-menu">
          <li><a href="<?= url('/pages/informasi/pengumuman.php') ?>">Pengumuman</a></li>
          <li><a href="<?= url('/pages/informasi/berita.php') ?>">Berita</a></li>
          <li><a href="<?= url('/pages/informasi/galeri.php') ?>">Galeri</a></li>
        </ul>
      </details>
    </li>

    <li class="<?= $active === 'umkm' ? 'active' : '' ?>"><a href="<?= url('/pages/umkm.php') ?>">UMKM</a></li>
    <li class="<?= $active === 'layanan' ? 'active' : '' ?>"><a href="<?= url('/pages/layanan.php') ?>">Layanan</a></li>
    <li class="<?= $active === 'kontak' ? 'active' : '' ?>"><a href="<?= url('/pages/kontak.php') ?>">Kontak</a></li>

    <?php if (is_admin()): ?>
      <li class="<?= $active === 'admin' ? 'active' : '' ?>"><a href="<?= url('/admin/index.php') ?>">CRUD</a></li>
    <?php endif; ?>

    <?php if (is_logged_in()): ?>
      <li class="dropdown auth <?= $active === 'akun' ? 'active' : '' ?>">
        <details>
          <summary><?= e((string)(current_user()['name'] ?? 'Akun')) ?> ▾</summary>
          <ul class="dropdown-menu">
            <li><a href="<?= url('/user/index.php') ?>">Akun Saya</a></li>
            <?php if (is_admin()): ?>
              <li><a href="<?= url('/admin/index.php') ?>">Dashboard Admin</a></li>
            <?php endif; ?>
            <li><a href="<?= url('/auth/logout.php') ?>">Logout</a></li>
          </ul>
        </details>
      </li>
    <?php else: ?>
      <li class="auth <?= $active === 'login' ? 'active' : '' ?>"><a href="<?= url('/auth/login.php') ?>">Login</a></li>
      <li class="auth <?= $active === 'register' ? 'active' : '' ?>"><a href="<?= url('/auth/register.php') ?>">Daftar</a></li>
    <?php endif; ?>
  </ul>
</div>
