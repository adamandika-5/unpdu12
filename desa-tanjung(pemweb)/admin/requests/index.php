<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Permohonan Layanan • ' . APP_NAME;
$active = 'admin';

$stmt = $pdo->query("SELECT sr.id, sr.note, sr.status, sr.created_at,
                            u.name AS user_name, u.username AS user_username,
                            s.name AS service_name
                     FROM service_requests sr
                     JOIN users u ON u.id = sr.user_id
                     JOIN services s ON s.id = sr.service_id
                     ORDER BY sr.created_at DESC");
$rows = $stmt->fetchAll();

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Permohonan Layanan</h2>
  <p class="muted">Daftar permohonan layanan yang diajukan warga.</p>

  <div style="height:14px"></div>

  <div class="card">
    <?php if (!$rows): ?>
      <div class="alert alert-info">Belum ada permohonan.</div>
    <?php else: ?>
      <table class="table">
        <thead>
          <tr>
            <th>Warga</th>
            <th>Layanan</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th style="width:180px">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td>
                <?= e($r['user_name']) ?><br>
                <span class="muted">@<?= e($r['user_username']) ?></span>
              </td>
              <td><?= e($r['service_name']) ?></td>
              <td><?= e(str_limit((string)($r['note'] ?? ''), 80)) ?></td>
              <td><span class="badge"><?= e($r['status']) ?></span></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
              <td>
                <a class="btn" href="<?= url('/admin/requests/edit.php?id=' . (int)$r['id']) ?>">Ubah Status</a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>