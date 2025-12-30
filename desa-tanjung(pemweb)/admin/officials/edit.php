<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(404); echo "Data tidak ditemukan."; exit; }

$stmt = $pdo->prepare("SELECT * FROM village_officials WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
if (!$row) { http_response_code(404); echo "Data tidak ditemukan."; exit; }

$title = 'Edit Perangkat Desa • ' . APP_NAME;
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

        $photoPath = $row['photo_path'] ?? null;
        if (!empty($_FILES['photo']['name'] ?? '')) {
            $photoPath = upload_image($_FILES['photo']);
        }

        $stmt = $pdo->prepare("UPDATE village_officials
                               SET name=:name, position=:pos, phone=:phone, email=:email,
                                   sort_order=:sort, photo_path=:photo
                               WHERE id=:id");
        $stmt->execute([
            ':name' => $name,
            ':pos' => $position,
            ':phone' => ($phone === '' ? null : $phone),
            ':email' => ($email === '' ? null : $email),
            ':sort' => $sort,
            ':photo' => $photoPath,
            ':id' => $id,
        ]);

        flash_set('success', 'Perangkat desa berhasil diperbarui.');
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
  <h2>Edit Perangkat Desa</h2>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" name="name" value="<?= e($row['name']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Jabatan</label>
        <input class="form-control" name="position" value="<?= e($row['position']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">No. HP (opsional)</label>
        <input class="form-control" name="phone" value="<?= e((string)($row['phone'] ?? '')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Email (opsional)</label>
        <input class="form-control" name="email" type="email" value="<?= e((string)($row['email'] ?? '')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Urutan tampil</label>
        <input class="form-control" name="sort_order" type="number" value="<?= e((string)($row['sort_order'] ?? 0)) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Foto (opsional)</label>
        <?php if (!empty($row['photo_path'])): ?>
          <div style="margin-bottom:8px">
            <img class="thumb" style="height:140px" src="<?= url('/' . $row['photo_path']) ?>" alt="">
          </div>
        <?php endif; ?>
        <input class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/officials/index.php') ?>">Kembali</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>