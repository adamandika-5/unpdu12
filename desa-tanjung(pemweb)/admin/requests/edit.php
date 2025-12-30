<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(404); echo "Permohonan tidak ditemukan."; exit; }

$stmt = $pdo->prepare("SELECT sr.id, sr.note, sr.status, sr.created_at,
                              u.name AS user_name, u.username AS user_username,
                              s.name AS service_name
                       FROM service_requests sr
                       JOIN users u ON u.id = sr.user_id
                       JOIN services s ON s.id = sr.service_id
                       WHERE sr.id = :id
                       LIMIT 1");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
if (!$row) { http_response_code(404); echo "Permohonan tidak ditemukan."; exit; }

$title = 'Ubah Status Permohonan • ' . APP_NAME;
$active = 'admin';
$error = '';

$statuses = ['baru', 'diproses', 'selesai', 'ditolak'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $status = $_POST['status'] ?? 'baru';
        if (!in_array($status, $statuses, true)) $status = 'baru';

        $stmt = $pdo->prepare("UPDATE service_requests SET status = :s WHERE id = :id");
        $stmt->execute([':s' => $status, ':id' => $id]);

        flash_set('success', 'Status permohonan berhasil diperbarui.');
        header('Location: ' . url('/admin/requests/index.php'));
        exit;

    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Ubah Status Permohonan</h2>
  <p class="muted">
    Warga: <strong><?= e($row['user_name']) ?></strong> (@<?= e($row['user_username']) ?>) <br>
    Layanan: <strong><?= e($row['service_name']) ?></strong>
  </p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:760px">
    <div class="muted">Catatan warga:</div>
    <div style="height:6px"></div>
    <div><?= nl2br(e((string)($row['note'] ?? '-'))) ?></div>

    <div style="height:14px"></div>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status">
          <?php foreach ($statuses as $s): ?>
            <option value="<?= e($s) ?>" <?= ($row['status'] === $s ? 'selected' : '') ?>><?= e($s) ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/requests/index.php') ?>">Kembali</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>