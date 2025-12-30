<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { http_response_code(404); echo "User tidak ditemukan."; exit; }

$stmt = $pdo->prepare("SELECT id, name, username, email, phone, role, status FROM users WHERE id = :id LIMIT 1");
$stmt->execute([':id' => $id]);
$row = $stmt->fetch();
if (!$row) { http_response_code(404); echo "User tidak ditemukan."; exit; }

$title = 'Edit User • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? $row['role'];
        $status = $_POST['status'] ?? $row['status'];
        $password = (string)($_POST['password'] ?? '');

        if ($name === '') throw new RuntimeException('Nama wajib diisi.');
        if (!in_array($role, ['admin','warga'], true)) $role = 'warga';
        if (!in_array($status, ['active','disabled'], true)) $status = 'active';

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Format email tidak valid.');
        }

        // Email unik (jika diisi)
        if ($email !== '') {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :e AND id <> :id LIMIT 1");
            $stmt->execute([':e' => $email, ':id' => $id]);
            if ($stmt->fetch()) throw new RuntimeException('Email sudah digunakan user lain.');
        }

        // Update dasar
        $stmt = $pdo->prepare("UPDATE users
                               SET name=:name, email=:email, phone=:phone, role=:role, status=:status
                               WHERE id=:id");
        $stmt->execute([
            ':name' => $name,
            ':email' => ($email === '' ? null : $email),
            ':phone' => ($phone === '' ? null : $phone),
            ':role' => $role,
            ':status' => $status,
            ':id' => $id,
        ]);

        // Update password (opsional)
        if ($password !== '') {
            if (strlen($password) < 6) throw new RuntimeException('Password minimal 6 karakter.');
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password_hash = :h WHERE id = :id");
            $stmt->execute([':h' => $hash, ':id' => $id]);
        }

        flash_set('success', 'User berhasil diperbarui.');
        header('Location: ' . url('/admin/users/index.php'));
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
  <h2>Edit User</h2>
  <p class="muted">Username: <code><?= e($row['username']) ?></code></p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:760px">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" name="name" value="<?= e($row['name']) ?>" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" name="email" type="email" value="<?= e((string)($row['email'] ?? '')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">No. HP</label>
        <input class="form-control" name="phone" value="<?= e((string)($row['phone'] ?? '')) ?>">
      </div>

      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role">
          <option value="warga" <?= ($row['role'] === 'warga' ? 'selected' : '') ?>>warga</option>
          <option value="admin" <?= ($row['role'] === 'admin' ? 'selected' : '') ?>>admin</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status">
          <option value="active" <?= ($row['status'] === 'active' ? 'selected' : '') ?>>active</option>
          <option value="disabled" <?= ($row['status'] === 'disabled' ? 'selected' : '') ?>>disabled</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Password Baru (opsional)</label>
        <input class="form-control" name="password" type="password" placeholder="Kosongkan jika tidak diubah">
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/users/index.php') ?>">Kembali</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>