<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$type = $_GET['type'] ?? 'berita';
if (!in_array($type, ['berita', 'pengumuman'], true)) $type = 'berita';

$title = 'Tambah ' . ucfirst($type) . ' • ' . APP_NAME;
$active = 'admin';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $judul = trim($_POST['judul'] ?? '');
        $ringkasan = trim($_POST['ringkasan'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($judul === '' || $content === '') {
            throw new RuntimeException('Judul dan isi wajib diisi.');
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = upload_image($_FILES['image']);
        }

        $stmt = $pdo->prepare("INSERT INTO posts (type, judul, ringkasan, content, image_path, created_at, updated_at)
                               VALUES (:type, :judul, :ringkasan, :content, :image_path, NOW(), NOW())");
        $stmt->execute([
            ':type' => $type,
            ':judul' => $judul,
            ':ringkasan' => $ringkasan,
            ':content' => $content,
            ':image_path' => $imagePath,
        ]);

        header('Location: ' . url('/admin/posts/index.php?type=' . $type));
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
  <h2>Tambah <?= e(ucfirst($type)) ?></h2>

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
        <label class="form-label">Ringkasan (opsional)</label>
        <input class="form-control" name="ringkasan">
      </div>

      <div class="form-group">
        <label class="form-label">Gambar (opsional)</label>
        <input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        <div class="muted">Maks 2MB.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Isi</label>
        <textarea class="form-control" name="content" rows="8" required></textarea>
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn" href="<?= url('/admin/posts/index.php?type=' . e($type)) ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>