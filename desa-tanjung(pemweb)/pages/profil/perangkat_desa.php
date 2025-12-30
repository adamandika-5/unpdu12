<?php
require_once __DIR__ . '/../../config/db.php';

$title = 'Perangkat Desa • ' . APP_NAME;
$active = 'profil';
$stmt = $pdo->query("SELECT id, name, position, photo_path, phone, email, sort_order FROM village_officials ORDER BY sort_order ASC, name ASC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Perangkat Desa</h2>
  <p class="muted">Daftar perangkat desa dan jabatan.</p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-info">Data perangkat desa belum diisi. Admin dapat mengisi melalui menu CRUD.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $o): ?>
        <div class="card">
          <?php if (!empty($o['photo_path'])): ?>
            <img class="thumb" src="<?= url('/' . $o['photo_path']) ?>" alt="<?= e($o['name']) ?>">
            <div style="height:10px"></div>
          <?php endif; ?>

          <h4><?= e($o['name']) ?></h4>
          <p class="muted"><?= e($o['position']) ?></p>

          <?php if (!empty($o['phone']) || !empty($o['email'])): ?>
            <div style="height:10px"></div>
            <div class="muted">
              <?php if (!empty($o['phone'])): ?>HP: <?= e($o['phone']) ?><br><?php endif; ?>
              <?php if (!empty($o['email'])): ?>Email: <?= e($o['email']) ?><?php endif; ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
