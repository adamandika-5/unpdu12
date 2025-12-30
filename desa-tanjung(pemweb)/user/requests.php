<?php
require_once __DIR__ . '/../config/db.php';
require_login();

$title = 'Permohonan Layanan • ' . APP_NAME;
$active = 'akun';

$user = current_user();

$stmt = $pdo->prepare("SELECT sr.id, sr.note, sr.status, sr.created_at, s.name AS service_name FROM service_requests sr JOIN services s ON s.id = sr.service_id WHERE sr.user_id = :uid ORDER BY sr.created_at DESC");
$stmt->execute([':uid' => (int)$user['id']]);
$rows = $stmt->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Permohonan Layanan</h2>
  <p class="muted">Riwayat permohonan layanan yang Anda ajukan.</p>

  <div style="height:14px"></div>

  <?php if (!$rows): ?>
    <div class="alert alert-info">Belum ada permohonan layanan.</div>
  <?php else: ?>
    <div class="card">
      <table class="table">
        <thead>
          <tr>
            <th>Layanan</th>
            <th>Catatan</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($rows as $r): ?>
            <tr>
              <td><?= e($r['service_name']) ?></td>
              <td><?= e(str_limit((string)($r['note'] ?? ''), 60)) ?></td>
              <td><span class="badge"><?= e($r['status']) ?></span></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
