<?php
require_once __DIR__ . '/../../config/db.php';
$title = 'Galeri • ' . APP_NAME;
$active = 'informasi';
$stmt = $pdo->query("SELECT id, judul, file_path, caption, created_at FROM gallery ORDER BY created_at DESC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Galeri</h2>
  <p class="muted">Dokumentasi kegiatan dan potret Desa Tanjung.</p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-danger">Belum ada foto di galeri.</div>
  <?php else: ?>
    <div class="grid" style="grid-template-columns:repeat(auto-fit,minmax(220px,1fr))">
      <?php foreach ($rows as $g): ?>
        <div class="card">
          <div style="height:160px; border-radius:14px; overflow:hidden; background:#f4f4f4; border:1px solid #eee;">
            <?php if (!empty($g['file_path'])): ?>
              <img src="<?= url('/' . $g['file_path']) ?>" alt="<?= e($g['judul']) ?>" style="width:100%;height:100%;object-fit:cover;">
            <?php endif; ?>
          </div>
          <div style="height:10px"></div>
          <h4><?= e($g['judul']) ?></h4>
          <?php if (!empty($g['caption'])): ?>
            <p class="muted"><?= e($g['caption']) ?></p>
          <?php endif; ?>
          <div class="muted"><?= e(date('d M Y', strtotime($g['created_at']))) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
