<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/gallery/index.php')); exit; }

$stmt = $pdo->prepare("DELETE FROM gallery WHERE id=:id");
$stmt->execute([':id'=>$id]);

header('Location: ' . url('/admin/gallery/index.php'));
exit;