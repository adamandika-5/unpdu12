<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Tambah Perangkat Desa • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $name = trim($_POST['name'] ?? '');
        $position = trim($_POST['position'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $sort = (int)($_POST['sort_order'] ?? 0);

        if ($name === '' || $position === '') {
            throw new RuntimeException('Nama dan jabatan wajib diisi.');
        }

        $photoPath = null;
        if (!empty($_FILES['photo']['name'] ?? '')) {
            $photoPath = upload_image($_FILES['photo']);
        }

        $stmt = $pdo->prepare("INSERT INTO village_officials (name, position, photo_path, phone, email, sort_order)
                               VALUES (:name, :pos, :photo, :phone, :email, :sort)");
        $stmt->execute([
            ':name' => $name,
            ':pos' => $position,
            ':photo' => $photoPath,
            ':phone' => ($phone === '' ? null : $phone),
            ':email' => ($email === '' ? null : $email),
            ':sort' => $sort,
        ]);

        flash_set('success', 'Perangkat desa berhasil ditambahkan.');
        header('Location: ' . url('/admin/officials/index.php'));
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
  <h2>Tambah Perangkat Desa</h2>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="form-group">
        <label class="form-label">Jabatan</label>
        <input class="form-control" name="position" required>
      </div>

      <div class="form-group">
        <label class="form-label">No. HP (opsional)</label>
        <input class="form-control" name="phone">
      </div>

      <div class="form-group">
        <label class="form-label">Email (opsional)</label>
        <input class="form-control" name="email" type="email">
      </div>

      <div class="form-group">
        <label class="form-label">Urutan tampil (angka kecil = lebih atas)</label>
        <input class="form-control" name="sort_order" type="number" value="0">
      </div>

      <div class="form-group">
        <label class="form-label">Foto (opsional)</label>
        <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/officials/index.php') ?>">Batal</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>