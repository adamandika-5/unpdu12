<?php
require_once __DIR__ . '/../config/db.php';

$title = 'Kontak • ' . APP_NAME;
$active = 'kontak';
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (!csrf_validate($_POST['csrf_token'] ?? null)) {
            throw new RuntimeException('CSRF token tidak valid. Silakan refresh halaman.');
        }

        $name   = trim($_POST['name'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $phone  = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '' || $subject === '' || $message === '') {
            throw new RuntimeException('Nama, subjek, dan pesan wajib diisi.');
        }

        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message, created_at) VALUES (:name, :email, :phone, :subject, :message, NOW())");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':phone' => $phone,
            ':subject' => $subject,
            ':message' => $message,
        ]);

        $success = 'Pesan Anda berhasil dikirim. Terima kasih!';
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
  <h2>Kontak</h2>
  <p class="muted">Hubungi pemerintah Desa Tanjung melalui form berikut.</p>

  <?php if ($success): ?>
    <div class="alert alert-success"><?= e($success) ?></div>
  <?php endif; ?>
  <?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
  <?php endif; ?>

  <div class="card">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">

      <div class="form-group">
        <label class="form-label">Nama</label>
        <input class="form-control" name="name" required>
      </div>

      <div class="form-group">
        <label class="form-label">Email</label>
        <input class="form-control" name="email" type="email" placeholder="opsional">
      </div>

      <div class="form-group">
        <label class="form-label">No. HP</label>
        <input class="form-control" name="phone" placeholder="opsional">
      </div>

      <div class="form-group">
        <label class="form-label">Subjek</label>
        <input class="form-control" name="subject" required>
      </div>

      <div class="form-group">
        <label class="form-label">Pesan</label>
        <textarea class="form-control" name="message" rows="5" required></textarea>
      </div>

      <button class="btn" type="submit">Kirim Pesan</button>
    </form>
  </div>
</main>

<?php include __DIR__ . '/../partials/footer.php'; ?>
