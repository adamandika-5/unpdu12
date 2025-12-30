<?php
require_once __DIR__ . '/../../config/db.php';
$type = 'berita';
$title = 'Berita Desa • ' . APP_NAME;
$active = 'informasi';
$stmt = $pdo->prepare("SELECT id, judul, ringkasan, created_at FROM posts WHERE type = :type ORDER BY created_at DESC");
$stmt->execute([':type' => $type]);
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Berita Desa</h2>
  <p class="muted">Berita dan kegiatan terbaru Desa Tanjung.</p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-danger">Belum ada data.</div>
  <?php else: ?>
    <div class="grid">
      <?php foreach ($rows as $r): ?>
        <div class="card">
          <span class="badge"><?= e(date('d M Y', strtotime($r['created_at']))) ?></span>
          <div style="height:10px"></div>
          <h4><?= e($r['judul']) ?></h4>
          <p class="muted"><?= e($r['ringkasan'] ?? '') ?></p>
          <div style="height:10px"></div>
          <a class="btn" href="<?= url('/pages/informasi/post_detail.php?id=' . (int)$r['id']) ?>">Baca</a>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
