<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../lib/content.php';

$slug = 'peta-desa';
$page = get_village_page($pdo, $slug);
$title = 'Peta Desa • ' . APP_NAME;
$active = 'profil';

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Peta Desa</h2>
  <p class="muted">Lokasi dan peta wilayah Desa Tanjung (Google Maps iframe).</p>

  <div style="height:14px"></div>

  <div class="card richtext">
    <?php if (!$page || empty($page['content'])): ?>
      <div class="alert alert-info">
        Konten peta belum diisi. Admin dapat menempel kode <strong>iframe Google Maps</strong> melalui menu CRUD.
      </div>
    <?php else: ?>
      <?= $page['content'] ?>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
