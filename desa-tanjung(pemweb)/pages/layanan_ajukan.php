<?php
require_once __DIR__ . '/../config/db.php';
require_login();

$title = 'Ajukan Layanan • ' . APP_NAME;
$active = 'layanan';
$serviceId = (int)($_GET['id'] ?? 0);
if ($serviceId <= 0) {
    http_response_code(404);
    echo "Layanan tidak ditemukan.";
    exit;
}

$stmt = $pdo->prepare("SELECT id, name, description, requirements FROM services WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $serviceId]);
$service = $stmt->fetch();

if (!$service) {
    http_response_code(404);
    echo "Layanan tidak ditemukan.";
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $note = trim($_POST['note'] ?? '');
        $stmt = $pdo->prepare("INSERT INTO service_requests (user_id, service_id, note, status) VALUES (:uid, :sid, :note, 'baru')");
        $stmt->execute([
            ':uid'  => (int)current_user()['id'],
            ':sid'  => (int)$service['id'],
            ':note' => ($note === '' ? null : $note),
        ]);

        flash_set('success', 'Permohonan layanan berhasil dikirim.');
        header('Location: ' . url('/user/requests.php'));
        exit;

    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Ajukan Layanan</h2>
  <p class="muted">Anda akan mengajukan permohonan untuk layanan: <strong><?= e($service['name']) ?></strong></p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:760px">
    <?php if (!empty($service['description'])): ?>
      <p><?= e($service['description']) ?></p>
    <?php endif; ?>

    <?php if (!empty($service['requirements'])): ?>
      <div style="height:10px"></div>
      <strong>Syarat:</strong>
      <div><?= nl2br(e($service['requirements'])) ?></div>
    <?php endif; ?>

    <div style="height:14px"></div>

    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Catatan (opsional)</label>
        <textarea class="form-control" name="note" rows="4" placeholder="Contoh: saya butuh surat pengantar..."></textarea>
      </div>

      <button class="btn" type="submit">Kirim Permohonan</button>
      <a class="btn btn-outline" href="<?= url('/pages/layanan.php') ?>">Kembali</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
