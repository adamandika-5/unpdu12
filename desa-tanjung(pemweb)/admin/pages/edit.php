<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    http_response_code(404);
    echo "Halaman tidak ditemukan.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM village_pages WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();

if (!$row) {
    http_response_code(404);
    echo "Halaman tidak ditemukan.";
    exit;
}

$title = 'Edit Halaman • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $titlePage = trim($_POST['title'] ?? '');
        $content = $_POST['content'] ?? '';

        if ($titlePage === '') {
            throw new RuntimeException('Judul wajib diisi.');
        }

        $stmt = $pdo->prepare("UPDATE village_pages
                               SET title = :title, content = :content, updated_at = NOW()
                               WHERE id = :id");
        $stmt->execute([
            ':title' => $titlePage,
            ':content' => $content,
            ':id' => $id,
        ]);

        flash_set('success', 'Halaman berhasil diperbarui.');
        header('Location: ' . url('/admin/pages/index.php'));
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
  <h2>Edit Halaman</h2>
  <p class="muted">Slug: <code><?= e($row['slug']) ?></code></p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Judul</label>
        <input class="form-control" name="title" value="<?= e($row['title']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Konten (HTML)</label>
        <textarea class="form-control" name="content" rows="14"><?= e($row['content'] ?? '') ?></textarea>
        <div class="muted" style="margin-top:8px">
          Untuk <strong>Peta Desa</strong>, tempel kode <code>&lt;iframe&gt;</code> Google Maps di sini.
        </div>
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/pages/index.php') ?>">Kembali</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>