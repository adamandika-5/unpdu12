<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/services/index.php')); exit; }

$rowStmt = $pdo->prepare("SELECT * FROM services WHERE id=:id");
$rowStmt->execute([':id'=>$id]);
$s = $rowStmt->fetch();
if (!$s) { http_response_code(404); echo "Data tidak ditemukan."; exit; }

$title = 'Edit Layanan • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) throw new RuntimeException('CSRF token tidak valid.');

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $requirements = trim($_POST['requirements'] ?? '');
        $form_link = trim($_POST['form_link'] ?? '');

        if ($name === '') throw new RuntimeException('Nama layanan wajib diisi.');

        $stmt = $pdo->prepare("UPDATE services SET name=:n, description=:d, requirements=:r, form_link=:f WHERE id=:id");
        $stmt->execute([':n'=>$name, ':d'=>$description, ':r'=>$requirements, ':f'=>$form_link, ':id'=>$id]);

        header('Location: ' . url('/admin/services/index.php'));
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
  <h2>Edit Layanan</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama Layanan</label>
        <input class="form-control" name="name" value="<?= e($s['name']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="3"><?= e($s['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Syarat</label>
        <textarea class="form-control" name="requirements" rows="4"><?= e($s['requirements'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Link Form (opsional)</label>
        <input class="form-control" name="form_link" value="<?= e($s['form_link'] ?? '') ?>">
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn" href="<?= url('/admin/services/index.php') ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>