<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: ' . url('/admin/officials/index.php')); exit; }

$stmt = $pdo->prepare("DELETE FROM village_officials WHERE id = :id");
$stmt->execute([':id' => $id]);

flash_set('success', 'Perangkat desa berhasil dihapus.');
header('Location: ' . url('/admin/officials/index.php'));
exit;