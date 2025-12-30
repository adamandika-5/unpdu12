<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$title = 'Tambah User • ' . APP_NAME;
$active = 'admin';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid.');
        }

        $name = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = $_POST['role'] ?? 'warga';
        $status = $_POST['status'] ?? 'active';
        $password = (string)($_POST['password'] ?? '');

        if ($name === '' || $username === '' || $password === '') {
            throw new RuntimeException('Nama, username, dan password wajib diisi.');
        }

        if (!in_array($role, ['admin','warga'], true)) $role = 'warga';
        if (!in_array($status, ['active','disabled'], true)) $status = 'active';

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Format email tidak valid.');
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        if ($stmt->fetch()) throw new RuntimeException('Username sudah digunakan.');

        if ($email !== '') {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :e LIMIT 1");
            $stmt->execute([':e' => $email]);
            if ($stmt->fetch()) throw new RuntimeException('Email sudah digunakan.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, phone, password_hash, role, status)
                               VALUES (:name, :username, :email, :phone, :hash, :role, :status)");
        $stmt->execute([
            ':name' => $name,
            ':username' => $username,
            ':email' => ($email === '' ? null : $email),
            ':phone' => ($phone === '' ? null : $phone),
            ':hash' => $hash,
            ':role' => $role,
            ':status' => $status,
        ]);

        flash_set('success', 'User berhasil ditambahkan.');
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
  <h2>Tambah User</h2>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:760px">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" name="username" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email (opsional)</label>
        <input class="form-control" name="email" type="email">
      </div>

      <div class="form-group">
        <label class="form-label">No. HP (opsional)</label>
        <input class="form-control" name="phone">
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" name="password" type="password" required>
      </div>

      <div class="form-group">
        <label class="form-label">Role</label>
        <select class="form-control" name="role">
          <option value="warga">warga</option>
          <option value="admin">admin</option>
        </select>
      </div>

      <div class="form-group">
        <label class="form-label">Status</label>
        <select class="form-control" name="status">
          <option value="active">active</option>
          <option value="disabled">disabled</option>
        </select>
      </div>

      <button class="btn" type="submit">Simpan</button>
      <a class="btn btn-outline" href="<?= url('/admin/users/index.php') ?>">Batal</a>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../../partials/footer.php'; ?>