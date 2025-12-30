<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Kelola Galeri • ' . APP_NAME;
$active = 'admin';

$rows = $pdo->query("SELECT id, judul, file_path, created_at FROM gallery ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Kelola Galeri</h2>
  <div class="actions">
    <a class="btn" href="<?= url('/admin/gallery/create.php') ?>">+ Tambah Foto</a>
    <a class="btn" href="<?= url('/admin/index.php') ?>">Kembali</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-danger">Belum ada foto.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Foto</th>
            <th>Judul</th>
            <th>Tanggal</th>
            <th style="width:120px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td>
                <?php if (!empty($r['file_path'])): ?>
                  <img src="<?= url('/' . $r['file_path']) ?>" alt="Foto" style="width:90px;height:60px;object-fit:cover;border-radius:10px;border:1px solid #eee;">
                <?php endif; ?>
              </td>
              <td><?= e($r['judul']) ?></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
              <td>
                <a class="btn" href="<?= url('/admin/gallery/delete.php?id=' . (int)$r['id']) ?>" onclick="return confirm('Hapus foto ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>