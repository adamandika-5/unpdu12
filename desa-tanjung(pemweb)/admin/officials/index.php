<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Kelola Perangkat Desa • ' . APP_NAME;
$active = 'admin';

$stmt = $pdo->query("SELECT id, name, position, photo_path, phone, email, sort_order, created_at
                     FROM village_officials
                     ORDER BY sort_order ASC, name ASC");
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
      <h2>Kelola Perangkat Desa</h2>
      <p class="muted">Tambah, ubah, atau hapus daftar perangkat desa.</p>
    </div>
    <a class="btn" href="<?= url('/admin/officials/create.php') ?>">+ Tambah Perangkat</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-info">Belum ada data perangkat desa.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Kontak</th>
            <th>Urutan</th>
            <th style="width:220px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['name']) ?></td>
              <td><?= e($r['position']) ?></td>
              <td class="muted">
                <?php if (!empty($r['phone'])): ?>HP: <?= e($r['phone']) ?><br><?php endif; ?>
                <?php if (!empty($r['email'])): ?>Email: <?= e($r['email']) ?><?php endif; ?>
              </td>
              <td><?= e((string)$r['sort_order']) ?></td>
              <td>
                <a class="btn" href="<?= url('/admin/officials/edit.php?id=' . (int)$r['id']) ?>">Edit</a>
                <a class="btn btn-outline" href="<?= url('/admin/officials/delete.php?id=' . (int)$r['id']) ?>"
                   onclick="return confirm('Hapus perangkat ini?')">Hapus</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>