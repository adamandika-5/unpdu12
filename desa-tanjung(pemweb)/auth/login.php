<?php
require_once __DIR__ . '/../config/db.php';

$title = 'Login • ' . APP_NAME;
$active = 'login';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid. Silakan refresh halaman.');
        }

        $identity = trim($_POST['identity'] ?? '');
        $password = (string)($_POST['password'] ?? '');

        if ($identity === '' || $password === '') {
            throw new RuntimeException('Username/Email dan password wajib diisi.');
        }

        $stmt = $pdo->prepare("SELECT id, name, username, email, phone, password_hash, role, status FROM users WHERE username = :u OR email = :e LIMIT 1");
        $stmt->execute([':u' => $identity, ':e' => $identity]);
        $user = $stmt->fetch();

        if (!$user || !password_verify($password, (string)$user['password_hash'])) {
            throw new RuntimeException('Login gagal. Username/Email atau password salah.');
        }

        if (($user['status'] ?? 'active') !== 'active') {
            throw new RuntimeException('Akun Anda sedang nonaktif. Hubungi admin.');
        }

        $_SESSION['user'] = [
            'id'       => (int)$user['id'],
            'name'     => (string)$user['name'],
            'username' => (string)$user['username'],
            'email'    => (string)($user['email'] ?? ''),
            'phone'    => (string)($user['phone'] ?? ''),
            'role'     => (string)($user['role'] ?? 'warga'),
        ];

        if (($user['role'] ?? 'warga') === 'admin') {
            header('Location: ' . url('/admin/index.php'));
        } else {
            header('Location: ' . url('/index.php'));
        }
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
  <h2>Login User</h2>
  <p class="muted">Masuk menggunakan akun masyarakat (warga) atau admin.</p>

  <div style="height:14px"></div>

  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card" style="max-width:520px">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Username / Email</label>
        <input class="form-control" name="identity" required>
      </div>

      <div class="form-group">
        <label class="form-label">Password</label>
        <input class="form-control" name="password" type="password" required>
      </div>

      <button class="btn" type="submit">Login</button>

      <div style="height:12px"></div>
      <p class="muted">Belum punya akun? <a href="<?= url('/auth/register.php') ?>"><strong>Daftar di sini</strong></a>.</p>
      
    </form>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
