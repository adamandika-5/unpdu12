<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Tambah Foto Galeri • ' . APP_NAME;
$active = 'admin';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) throw new RuntimeException('CSRF token tidak valid.');

        $judul = trim($_POST['judul'] ?? '');
        $caption = trim($_POST['caption'] ?? '');

        if ($judul === '') throw new RuntimeException('Judul wajib diisi.');
        if (empty($_FILES['file']['name'])) throw new RuntimeException('File foto wajib dipilih.');

        $path = upload_image($_FILES['file']);

        $stmt = $pdo->prepare("INSERT INTO gallery (judul, caption, file_path, created_at) VALUES (:j, :c, :p, NOW())");
        $stmt->execute([':j'=>$judul, ':c'=>$caption, ':p'=>$path]);

        header('Location: ' . url('/admin/gallery/index.php'));
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
  <h2>Tambah Foto Galeri</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Judul</label>
        <input class="form-control" name="judul" required>
      </div>

      <div class="form-group">
        <label class="form-label">Keterangan (opsional)</label>
        <input class="form-control" name="caption">
      </div>

      <div class="form-group">
        <label class="form-label">File Foto</label>
        <input class="form-control" type="file" name="file" accept=".jpg,.jpeg,.png,.webp" required>
        <div class="muted">Maks 2MB.</div>
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn" href="<?= url('/admin/gallery/index.php') ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>