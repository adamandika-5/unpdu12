<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$type = $_GET['type'] ?? 'berita';
if (!in_array($type, ['berita', 'pengumuman'], true)) $type = 'berita';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/posts/index.php?type=' . $type)); exit; }

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id=:id AND type=:type");
$stmt->execute([':id'=>$id, ':type'=>$type]);
$row = $stmt->fetch();
if (!$row) { http_response_code(404); echo "Data tidak ditemukan."; exit; }

$title = 'Edit ' . ucfirst($type) . ' • ' . APP_NAME;
$active = 'admin';
$error = '';

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

        $imagePath = $row['image_path'];
        if (!empty($_FILES['image']['name'])) {
            $imagePath = upload_image($_FILES['image']);
        }

        $stmt = $pdo->prepare("UPDATE posts SET judul=:judul, ringkasan=:ringkasan, content=:content, image_path=:image_path, updated_at=NOW()
                               WHERE id=:id AND type=:type");
        $stmt->execute([
            ':judul'=>$judul,
            ':ringkasan'=>$ringkasan,
            ':content'=>$content,
            ':image_path'=>$imagePath,
            ':id'=>$id,
            ':type'=>$type
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
  <h2>Edit <?= e(ucfirst($type)) ?></h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Judul</label>
        <input class="form-control" name="judul" value="<?= e($row['judul']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Ringkasan (opsional)</label>
        <input class="form-control" name="ringkasan" value="<?= e($row['ringkasan'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Gambar (opsional)</label>
        <?php if (!empty($row['image_path'])): ?>
          <div class="muted">Gambar saat ini:</div>
          <img src="<?= url('/' . $row['image_path']) ?>" alt="Gambar" style="width:100%; max-height:220px; object-fit:cover; border-radius:14px; border:1px solid #eee; margin:8px 0;">
        <?php endif; ?>
        <input class="form-control" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
        <div class="muted">Kosongkan jika tidak ingin mengganti. Maks 2MB.</div>
      </div>

      <div class="form-group">
        <label class="form-label">Isi</label>
        <textarea class="form-control" name="content" rows="8" required><?= e($row['content'] ?? '') ?></textarea>
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn" href="<?= url('/admin/posts/index.php?type=' . e($type)) ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>