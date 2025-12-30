<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Kelola Halaman Profil • ' . APP_NAME;
$active = 'admin';

$stmt = $pdo->query("SELECT id, slug, title, updated_at, created_at
                     FROM village_pages
                     ORDER BY slug ASC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <div class="actions">
    <div>
      <h2>Kelola Halaman Profil</h2>
      <p class="muted">Konten dinamis untuk menu Profil Desa (wilayah, sejarah, visi &amp; misi, peta, data desa).</p>
    </div>
    <a class="btn" href="<?= url('/admin/pages/create.php') ?>">+ Tambah Halaman</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-info">Belum ada halaman.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Slug</th>
            <th>Judul</th>
            <th>Update</th>
            <th style="width:220px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><code><?= e($r['slug']) ?></code></td>
              <td><?= e($r['title']) ?></td>
              <td><?= e(date('d M Y H:i', strtotime($r['updated_at'] ?? $r['created_at']))) ?></td>
              <td>
                <a class="btn" href="<?= url('/admin/pages/edit.php?id=' . (int)$r['id']) ?>">Edit</a>
                <a class="btn btn-outline" href="<?= url('/admin/pages/delete.php?id=' . (int)$r['id']) ?>"
                   onclick="return confirm('Hapus halaman ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="height:12px"></div>
      <div class="muted">
        <strong>Catatan:</strong> Untuk menu profil yang sudah ada, gunakan slug:
        <code>wilayah</code>, <code>sejarah</code>, <code>visi-misi</code>, <code>peta-desa</code>, <code>data-desa</code>.
      </div>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>