<?php
require_once __DIR__ . '/../config/db.php';

$title = 'UMKM • ' . APP_NAME;
$active = 'umkm';
$stmt = $pdo->query("SELECT id, name, owner, description, address, phone, photo_path, created_at FROM umkm ORDER BY created_at DESC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>UMKM Desa</h2>
  <p class="muted">Daftar pelaku UMKM dan produk unggulan di Desa Tanjung.</p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-danger">Belum ada data UMKM.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $u): ?>
        <div class="card">
          <?php if (!empty($u['photo_path'])): ?>
            <div style="height:160px; border-radius:14px; overflow:hidden; background:#f4f4f4; border:1px solid #eee;">
              <img src="<?= url('/' . $u['photo_path']) ?>" alt="<?= e($u['name']) ?>" style="width:100%;height:100%;object-fit:cover;">
            </div>
            <div style="height:10px"></div>
          <?php endif; ?>

          <h4><?= e($u['name']) ?></h4>
          <p class="muted">Pemilik: <?= e($u['owner'] ?? '-') ?></p>
          <p class="muted"><?= e($u['description'] ?? '') ?></p>

          <div style="height:8px"></div>
          <div class="muted">Alamat: <?= e($u['address'] ?? '-') ?></div>
          <div class="muted">Kontak: <?= e($u['phone'] ?? '-') ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
