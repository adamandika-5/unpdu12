<?php
require_once __DIR__ . '/config/db.php';

$title = 'Beranda • ' . APP_NAME;
$active = 'home';

$latestBerita = $pdo->prepare("SELECT id, judul, ringkasan, created_at FROM posts WHERE type='berita' ORDER BY created_at DESC LIMIT 3");
$latestBerita->execute();
$berita = $latestBerita->fetchAll();
$latestPeng = $pdo->prepare("SELECT id, judul, ringkasan, created_at FROM posts WHERE type='pengumuman' ORDER BY created_at DESC LIMIT 3");
$latestPeng->execute();
$pengumuman = $latestPeng->fetchAll();
$latestGal = $pdo->prepare("SELECT id, judul, file_path, created_at FROM gallery ORDER BY created_at DESC LIMIT 6");
$latestGal->execute();
$galeri = $latestGal->fetchAll();
?>
<?php include __DIR__ . '/partials/head.php'; ?>


<header class="hero">
  <div class="hero-slides">
  <div class="hero-slide" style="background-image:url('assets/img/slide1.jpg')"></div>
 <div class="hero-slide active" style="background-image:url('<?= url('/assets/img/slider2.jpg') ?>')"></div>
  <div class="hero-slide" style="background-image:url('<?= url('/assets/img/slider3.jpg') ?>')"></div>
</div>

  <div class="wrap">
  <?php include __DIR__ . '/partials/navbar.php'; ?>
  </div>

  <div class="hero-content">
    <h1>Desa Tanjung</h1>
    <h3>Website pusat informasi dan transparansi desa Tanjung.</h3>

    <div class="hero-actions">
      <a class="btn" href="<?= url('/pages/profil/visi_misi.php') ?>">Visi &amp; Misi</a>
    </div>
  </div>
</header>

<main class="container">
  <h2 class="section-title">Informasi Terbaru</h2>

  <div class="grid">
    <div class="card">
      <h4>Pengumuman</h4>
      <p class="muted">Update pengumuman terbaru dari Desa Tanjung.</p>
      <div style="height:10px"></div>
      <?php if (!$pengumuman): ?>
        <p class="muted">Belum ada pengumuman.</p>
      <?php else: ?>
        <ul style="padding-left:18px">
          <?php foreach ($pengumuman as $p): ?>
            <li style="margin-bottom:8px">
              <a href="<?= url('/pages/informasi/post_detail.php?id=' . (int)$p['id']) ?>">
                <?= e($p['judul']) ?>
              </a>
              <div class="muted"><?= e(date('d M Y', strtotime($p['created_at']))) ?></div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/pages/informasi/pengumuman.php') ?>">Lihat Semua</a>
    </div>

    <div class="card">
      <h4>Berita Desa</h4>
      <p class="muted">Berita dan kegiatan terbaru Desa Tanjung.</p>
      <div style="height:10px"></div>
      <?php if (!$berita): ?>
        <p class="muted">Belum ada berita.</p>
      <?php else: ?>
        <ul style="padding-left:18px">
          <?php foreach ($berita as $b): ?>
            <li style="margin-bottom:8px">
              <a href="<?= url('/pages/informasi/post_detail.php?id=' . (int)$b['id']) ?>">
                <?= e($b['judul']) ?>
              </a>
              <div class="muted"><?= e(date('d M Y', strtotime($b['created_at']))) ?></div>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/pages/informasi/berita.php') ?>">Lihat Semua</a>
    </div>

    <div class="card">
      <h4>Galeri</h4>
      <p class="muted">Cuplikan dokumentasi kegiatan Desa.</p>
      <div style="height:10px"></div>
      <?php if (!$galeri): ?>
        <p class="muted">Belum ada foto galeri.</p>
      <?php else: ?>
        <div class="grid" style="grid-template-columns:repeat(3,1fr); gap:10px">
      <?php foreach ($galeri as $g): ?>
        <a href="<?= url('/pages/informasi/galeri.php') ?>" title="<?= e($g['judul']) ?>">
          <div style="height:70px; border-radius:12px; border:1px solid #eee; overflow:hidden; background:#f4f4f4;">
            <?php if (!empty($g['file_path'])): ?>
              <img src="<?= url('/' . $g['file_path']) ?>" alt="<?= e($g['judul']) ?>" style="width:100%;height:100%;object-fit:cover;">
            <?php endif; ?>
          </div>
        </a>
      <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/pages/informasi/galeri.php') ?>">Buka Galeri</a>
    </div>
  </div>
</main>

<?php include __DIR__ . '/partials/footer.php'; ?>
