<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../lib/content.php';

$slug = 'wilayah';
$page = get_village_page($pdo, $slug);
$title = 'Wilayah • ' . APP_NAME;
$active = 'profil';

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Wilayah Desa</h2>
  <p class="muted">Informasi batas wilayah, dusun/RT/RW, dan kondisi geografis.</p>

  <div style="height:14px"></div>

  <div class="card richtext">
    <?php if (!$page || empty($page['content'])): ?>
      <div class="alert alert-info">Konten belum diisi. Admin dapat mengisi melalui menu CRUD.</div>
    <?php else: ?>
      <?= $page['content'] ?>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
