<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$type = $_GET['type'] ?? 'berita';
if (!in_array($type, ['berita', 'pengumuman'], true)) {
    $type = 'berita';
}

$title = 'Kelola ' . ucfirst($type) . ' • ' . APP_NAME;
$active = 'admin';

$stmt = $pdo->prepare("SELECT id, judul, created_at FROM posts WHERE type = :type ORDER BY created_at DESC");
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
  <h2>Kelola <?= e(ucfirst($type)) ?></h2>
  <div class="actions">
    <a class="btn" href="<?= url('/admin/posts/create.php?type=' . e($type)) ?>">+ Tambah</a>
    <a class="btn" href="<?= url('/admin/index.php') ?>">Kembali</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-danger">Belum ada data.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Judul</th>
            <th>Tanggal</th>
            <th style="width:220px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['judul']) ?></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
              <td class="actions">
                <a class="btn" href="<?= url('/admin/posts/edit.php?id=' . (int)$r['id'] . '&type=' . e($type)) ?>">Edit</a>
                <a class="btn" href="<?= url('/admin/posts/delete.php?id=' . (int)$r['id'] . '&type=' . e($type)) ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>