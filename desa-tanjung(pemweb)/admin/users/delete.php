<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: ' . url('/admin/users/index.php')); exit; }

// Hindari hapus diri sendiri
if ($id === (int)current_user()['id']) {
    flash_set('danger', 'Tidak bisa menghapus akun yang sedang digunakan.');
    header('Location: ' . url('/admin/users/index.php'));
    exit;
}

$stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
$stmt->execute([':id' => $id]);

flash_set('success', 'User berhasil dihapus.');
header('Location: ' . url('/admin/users/index.php'));
exit;