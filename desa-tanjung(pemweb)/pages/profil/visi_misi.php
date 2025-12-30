<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../lib/content.php';

$slug = 'visi-misi';
$page = get_village_page($pdo, $slug);
$title = 'Visi & Misi • ' . APP_NAME;
$active = 'profil';

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Visi &amp; Misi</h2>
  <p class="muted">Arah pembangunan dan tujuan Desa Tanjung.</p>

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
