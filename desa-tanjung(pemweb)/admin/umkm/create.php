<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Tambah UMKM • ' . APP_NAME;
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

        $photo = null;
        if (!empty($_FILES['photo']['name'])) {
            $photo = upload_image($_FILES['photo']);
        }

        $stmt = $pdo->prepare("INSERT INTO umkm (name, owner, description, address, phone, photo_path, created_at)
                               VALUES (:n,:o,:d,:a,:p,:photo,NOW())");
        $stmt->execute([
            ':n'=>$name, ':o'=>$owner, ':d'=>$description, ':a'=>$address, ':p'=>$phone, ':photo'=>$photo
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
  <h2>Tambah UMKM</h2>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama Usaha</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="form-group">
        <label class="form-label">Pemilik</label>
        <input class="form-control" name="owner">
      </div>

      <div class="form-group">
        <label class="form-label">Deskripsi</label>
        <textarea class="form-control" name="description" rows="4"></textarea>
      </div>

      <div class="form-group">
        <label class="form-label">Alamat</label>
        <input class="form-control" name="address">
      </div>

      <div class="form-group">
        <label class="form-label">Kontak (No. HP/WA)</label>
        <input class="form-control" name="phone">
      </div>

      <div class="form-group">
        <label class="form-label">Foto (opsional)</label>
        <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <div class="actions">
        <button class="btn" type="submit">Simpan</button>
        <a class="btn" href="<?= url('/admin/umkm/index.php') ?>">Batal</a>
      </div>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>