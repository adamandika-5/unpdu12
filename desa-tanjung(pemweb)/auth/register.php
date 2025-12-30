<?php
require_once __DIR__ . '/../config/db.php';

if (is_logged_in()) {
    header('Location: ' . url('/index.php'));
    exit;
}

$title = 'Daftar • ' . APP_NAME;
$active = 'register';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid. Silakan refresh halaman.');
        }

        $name     = trim($_POST['name'] ?? '');
        $username = trim($_POST['username'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $phone    = trim($_POST['phone'] ?? '');
        $pass1    = (string)($_POST['password'] ?? '');
        $pass2    = (string)($_POST['password2'] ?? '');

        if ($name === '' || $username === '' || $pass1 === '') {
            throw new RuntimeException('Nama, username, dan password wajib diisi.');
        }
        if (strlen($username) < 4) {
            throw new RuntimeException('Username minimal 4 karakter.');
        }
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('Format email tidak valid.');
        }
        if (strlen($pass1) < 6) {
            throw new RuntimeException('Password minimal 6 karakter.');
        }
        if ($pass1 !== $pass2) {
            throw new RuntimeException('Konfirmasi password tidak sama.');
        }

        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u LIMIT 1");
        $stmt->execute([':u' => $username]);
        if ($stmt->fetch()) {
            throw new RuntimeException('Username sudah digunakan. Silakan pilih username lain.');
        }

        if ($email !== '') {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = :e LIMIT 1");
            $stmt->execute([':e' => $email]);
            if ($stmt->fetch()) {
                throw new RuntimeException('Email sudah digunakan. Silakan gunakan email lain.');
            }
        }

        $hash = password_hash($pass1, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (name, username, email, phone, password_hash, role, status)
                               VALUES (:name, :username, :email, :phone, :hash, 'warga', 'active')");
        $stmt->execute([
            ':name'     => $name,
            ':username' => $username,
            ':email'    => ($email === '' ? null : $email),
            ':phone'    => ($phone === '' ? null : $phone),
            ':hash'     => $hash,
        ]);

        $uid = (int)$pdo->lastInsertId();

        $_SESSION['user'] = [
            'id'       => $uid,
            'name'     => $name,
            'username' => $username,
            'email'    => $email,
            'phone'    => $phone,
            'role'     => 'warga',
        ];

        flash_set('success', 'Pendaftaran berhasil. Selamat datang!');
        header('Location: ' . url('/index.php'));
        exit;

    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

include __DIR__ . '/../partials/head.php';
?>
<div class="page-header">
  <div class="wrap">
    <?php include __DIR__ . '/../partials/navbar.php'; ?>
  </div>
</div>

<main class="container">
  <h2>Daftar Akun Masyarakat</h2>
  <p class="muted">Buat akun untuk login ke website Desa Tanjung.</p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:620px">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama Lengkap</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="form-group">
        <label class="form-label">Username</label>
        <input class="form-control" name="username" required>
        <div class="muted" style="margin-top:6px">Minimal 4 karakter, tanpa spasi.</div>
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
        <label class="form-label">Konfirmasi Password</label>
        <input class="form-control" name="password2" type="password" required>
      </div>

      <button class="btn" type="submit">Daftar</button>

      <div style="height:12px"></div>
      <p class="muted">Sudah punya akun? <a href="<?= url('/auth/login.php') ?>"><strong>Login</strong></a>.</p>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
