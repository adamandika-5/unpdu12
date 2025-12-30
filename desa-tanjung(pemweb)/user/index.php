<?php
require_once __DIR__ . '/../config/db.php';
require_login();

$title = 'Akun Saya • ' . APP_NAME;
$active = 'akun';
$user = current_user();
$stmt = $pdo->prepare("SELECT sr.id, sr.status, sr.created_at, s.name AS service_name FROM service_requests sr JOIN services s ON s.id = sr.service_id WHERE sr.user_id = :uid ORDER BY sr.created_at DESC LIMIT 5");
$stmt->execute([':uid' => (int)$user['id']]);
$requests = $stmt->fetchAll();

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Akun Saya</h2>
  <p class="muted">Informasi akun masyarakat (warga) yang sedang login.</p>

  <div style="height:14px"></div>

  <div class="card">
    <div><strong>Nama:</strong> <?= e((string)($user['name'] ?? '')) ?></div>
    <div><strong>Username:</strong> <?= e((string)($user['username'] ?? '')) ?></div>
    <div><strong>Email:</strong> <?= e((string)($user['email'] ?? '-')) ?></div>
    <div><strong>No. HP:</strong> <?= e((string)($user['phone'] ?? '-')) ?></div>
    <div><strong>Role:</strong> <?= e((string)($user['role'] ?? 'warga')) ?></div>
  </div>
  <div style="height:14px"></div>

  <h3>Permohonan Layanan Terakhir</h3>
  <p class="muted">Daftar singkat permohonan layanan yang pernah Anda ajukan.</p>
  <div style="height:10px"></div>

  <?php if (!$requests): ?>
    <div class="alert alert-info">Belum ada permohonan layanan.</div>
  <?php else: ?>
    <div class="card">
      <table class="table">
        <thead>
          <tr>
            <th>Layanan</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($requests as $r): ?>
            <tr>
              <td><?= e($r['service_name']) ?></td>
              <td><span class="badge"><?= e($r['status']) ?></span></td>
              <td><?= e(date('d M Y H:i', strtotime($r['created_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <div style="height:10px"></div>
      <a class="btn" href="<?= url('/user/requests.php') ?>">Lihat Semua Permohonan</a>
    </div>
  <?php endif; ?>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
