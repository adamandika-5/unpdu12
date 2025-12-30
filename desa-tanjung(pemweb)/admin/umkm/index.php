<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Kelola UMKM • ' . APP_NAME;
$active = 'admin';

$rows = $pdo->query("SELECT id, name, owner, created_at FROM umkm ORDER BY created_at DESC")->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Kelola UMKM</h2>
  <div class="actions">
    <a class="btn" href="<?= url('/admin/umkm/create.php') ?>">+ Tambah UMKM</a>
    <a class="btn" href="<?= url('/admin/index.php') ?>">Kembali</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-danger">Belum ada data UMKM.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Nama Usaha</th>
            <th>Pemilik</th>
            <th>Tanggal</th>
            <th style="width:240px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['name']) ?></td>
              <td><?= e($r['owner'] ?? '-') ?></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
              <td class="actions">
                <a class="btn" href="<?= url('/admin/umkm/edit.php?id=' . (int)$r['id']) ?>">Edit</a>
                <a class="btn" href="<?= url('/admin/umkm/delete.php?id=' . (int)$r['id']) ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>