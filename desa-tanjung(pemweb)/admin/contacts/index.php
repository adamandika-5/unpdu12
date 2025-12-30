<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Pesan Kontak • ' . APP_NAME;
$active = 'admin';

$rows = $pdo->query("SELECT id, name, email, phone, subject, message, created_at FROM contact_messages ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Pesan Kontak</h2>
  <div class="actions">
    <a class="btn" href="<?= url('/admin/index.php') ?>">Kembali</a>
  </div>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-danger">Belum ada pesan masuk.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $m): ?>
        <div class="card">
          <span class="badge"><?= e(date('d M Y H:i', strtotime($m['created_at']))) ?></span>
          <div style="height:10px"></div>
          <h4><?= e($m['subject']) ?></h4>
          <p class="muted">Dari: <?= e($m['name']) ?><?= $m['email'] ? ' • ' . e($m['email']) : '' ?><?= $m['phone'] ? ' • ' . e($m['phone']) : '' ?></p>
          <div style="height:8px"></div>
          <div><?= nl2br(e($m['message'])) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>