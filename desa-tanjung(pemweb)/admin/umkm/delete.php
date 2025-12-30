<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/umkm/index.php')); exit; }

$stmt = $pdo->prepare("DELETE FROM umkm WHERE id=:id");
$stmt->execute([':id'=>$id]);

header('Location: ' . url('/admin/umkm/index.php'));
exit;