<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/umkm/index.php')); exit; }

$row = $pdo->prepare("SELECT * FROM umkm WHERE id=:id");
$row->execute([':id'=>$id]);
$u = $row->fetch();
if (!$u) { http_response_code(404); echo "Data tidak ditemukan."; exit; }

$title = 'Edit UMKM • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) throw new RuntimeException('CSRF token tidak valid.');

        $name = trim($_POST['name'] ?? '');
        $owner = trim($_POST['owner'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($name === '') throw new RuntimeException('Nama usaha wajib diisi.');

        $photo = $u['photo_path'];
        if (!empty($_FILES['photo']['name'])) {
            $photo = upload_image($_FILES['photo']);
        }

        $stmt = $pdo->prepare("UPDATE umkm SET name=:n, owner=:o, description=:d, address=:a, phone=:p, photo_path=:photo WHERE id=:id");
        $stmt->execute([
            ':n'=>$name, ':o'=>$owner, ':d'=>$description, ':a'=>$address, ':p'=>$phone, ':photo'=>$photo, ':id'=>$id
        ]);

        header('Location: ' . url('/admin/umkm/index.php'));
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
  <h2>Edit UMKM</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama Usaha</label>
        <input class="form-control" name="name" value="<?= e($u['name']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Pemilik</label>
        <input class="form-control" name="owner" value="<?= e($u['owner'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="4"><?= e($u['description'] ?? '') ?></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat</label>
        <input class="form-control" name="address" value="<?= e($u['address'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Kontak (No. HP/WA)</label>
        <input class="form-control" name="phone" value="<?= e($u['phone'] ?? '') ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Foto (opsional)</label>
        <?php if (!empty($u['photo_path'])): ?>
          <div class="muted">Foto saat ini:</div>
          <img src="<?= url('/' . $u['photo_path']) ?>" alt="Foto" style="width:100%; max-height:220px; object-fit:cover; border-radius:14px; border:1px solid #eee; margin:8px 0;">
        <?php endif; ?>
        <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
        <div class="muted">Kosongkan jika tidak ingin mengganti.</div>
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan Perubahan</button>
        <a class="btn" href="<?= url('/admin/umkm/index.php') ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>