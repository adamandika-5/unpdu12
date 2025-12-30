<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Kelola User • ' . APP_NAME;
$active = 'admin';

$stmt = $pdo->query("SELECT id, name, username, email, phone, role, status, created_at FROM users ORDER BY created_at DESC");
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
      <h2>Kelola User</h2>
      <p class="muted">Kelola akun masyarakat (warga) dan admin.</p>
    </div>
    <a class="btn" href="<?= url('/admin/users/create.php') ?>">+ Tambah User</a>
  </div>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-info">Belum ada user.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Username</th>
            <th>Email/HP</th>
            <th>Role</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th style="width:220px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $u): ?>
            <tr>
              <td><?= e($u['name']) ?></td>
              <td><?= e($u['username']) ?></td>
              <td class="muted">
                <?= e((string)($u['email'] ?? '-')) ?><br>
                <?= e((string)($u['phone'] ?? '-')) ?>
              </td>
              <td><span class="badge"><?= e($u['role']) ?></span></td>
              <td><?= e($u['status']) ?></td>
              <td><?= e(date('d M Y', strtotime($u['created_at']))) ?></td>
              <td>
                <a class="btn" href="<?= url('/admin/users/edit.php?id=' . (int)$u['id']) ?>">Edit</a>
                <?php if ((int)$u['id'] !== (int)current_user()['id']): ?>
                  <a class="btn btn-outline" href="<?= url('/admin/users/delete.php?id=' . (int)$u['id']) ?>"
                     onclick="return confirm('Hapus user ini?')">Hapus</a>
                <?php else: ?>
                  <span class="muted">-</span>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>