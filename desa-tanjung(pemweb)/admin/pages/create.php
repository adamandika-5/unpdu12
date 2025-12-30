<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Tambah Halaman Profil • ' . APP_NAME;
$active = 'admin';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $slug  = trim($_POST['slug'] ?? '');
        $titlePage = trim($_POST['title'] ?? '');
        $content = $_POST['content'] ?? '';

        if ($slug === '' || $titlePage === '') {
            throw new RuntimeException('Slug dan judul wajib diisi.');
        }

        if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            throw new RuntimeException('Slug hanya boleh huruf kecil, angka, dan tanda minus (-).');
        }

        $stmt = $pdo->prepare("SELECT id FROM village_pages WHERE slug = :s LIMIT 1");
        $stmt->execute([':s' => $slug]);
        if ($stmt->fetch()) {
            throw new RuntimeException('Slug sudah ada. Gunakan slug lain.');
        }

        $stmt = $pdo->prepare("INSERT INTO village_pages (slug, title, content, created_at, updated_at)
                               VALUES (:slug, :title, :content, NOW(), NOW())");
        $stmt->execute([
            ':slug' => $slug,
            ':title' => $titlePage,
            ':content' => $content,
        ]);

        flash_set('success', 'Halaman berhasil ditambahkan.');
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
  <h2>Tambah Halaman Profil</h2>
  <p class="muted">Anda dapat menulis konten dalam bentuk HTML (termasuk iframe untuk Google Maps).</p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Slug</label>
        <input class="form-control" name="slug" placeholder="contoh: wilayah" required>
      </div>

      <div class="form-group">
        <label class="form-label">Judul</label>
        <input class="form-control" name="title" placeholder="contoh: Wilayah Desa" required>
      </div>

      <div class="form-group">
        <label class="form-label">Konten (HTML)</label>
        <textarea class="form-control" name="content" rows="12" placeholder="<p>Isi konten...</p>"></textarea>
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/pages/index.php') ?>">Batal</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>