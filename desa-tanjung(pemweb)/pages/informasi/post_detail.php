<?php
require_once __DIR__ . '/../../config/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: ' . url('/index.php'));
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
$stmt->execute([':id' => $id]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    echo "Konten tidak ditemukan.";
    exit;
}

$title = $post['judul'] . ' • ' . APP_NAME;
$active = 'informasi';
include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <div class="card">
    <span class="badge"><?= e(ucfirst($post['type'])) ?> • <?= e(date('d M Y', strtotime($post['created_at']))) ?></span>
    <div style="height:10px"></div>
    <h2><?= e($post['judul']) ?></h2>
    <?php if (!empty($post['image_path'])): ?>
      <div style="height:12px"></div>
      <img src="<?= url('/' . $post['image_path']) ?>" alt="<?= e($post['judul']) ?>" style="width:100%; max-height:420px; object-fit:cover; border-radius:14px; border:1px solid #eee;">
    <?php endif; ?>
    <div style="height:14px"></div>
    <?php if (!empty($post['ringkasan'])): ?>
      <p class="muted"><?= e($post['ringkasan']) ?></p>
      <div style="height:10px"></div>
    <?php endif; ?>
    <div>
      <?= nl2br(e($post['content'] ?? '')) ?>
    </div>
    <div style="height:16px"></div>
    <div class="actions">
      <?php if ($post['type'] === 'berita'): ?>
        <a class="btn" href="<?= url('/pages/informasi/berita.php') ?>">Kembali ke Berita</a>
      <?php else: ?>
        <a class="btn" href="<?= url('/pages/informasi/pengumuman.php') ?>">Kembali ke Pengumuman</a>
      <?php endif; ?>
    </div>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>
