<?php
require_once __DIR__ . '/../config/db.php';

$title = 'Layanan • ' . APP_NAME;
$active = 'layanan';
$stmt = $pdo->query("SELECT id, name, description, requirements, form_link, created_at FROM services ORDER BY created_at DESC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Layanan Desa</h2>
  <p class="muted">
    Daftar layanan yang tersedia di Desa Tanjung.
    <?php if (!is_logged_in()): ?>
      <br>Untuk mengajukan permohonan layanan, silakan <a href="<?= url('/auth/login.php') ?>"><strong>login</strong></a>.
    <?php endif; ?>
  </p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-danger">Belum ada data layanan.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $s): ?>
        <div class="card">
          <h4><?= e($s['name']) ?></h4>
          <p class="muted"><?= e($s['description'] ?? '') ?></p>

          <?php if (!empty($s['requirements'])): ?>
            <div style="height:8px"></div>
            <strong>Syarat:</strong>
            <div><?= nl2br(e($s['requirements'])) ?></div>
          <?php endif; ?>

          <div style="height:10px"></div>

          <div class="actions">
            <a class="btn" href="<?= url('/pages/layanan_ajukan.php?id=' . (int)$s['id']) ?>">Ajukan</a>

            <?php if (!empty($s['form_link'])): ?>
              <a class="btn btn-outline" target="_blank" rel="noopener" href="<?= e($s['form_link']) ?>">Buka Form</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
