<?php
require_once __DIR__ . '/../../config/db.php';
require_admin();

$type = $_GET['type'] ?? 'berita';
if (!in_array($type, ['berita', 'pengumuman'], true)) $type = 'berita';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) { header('Location: ' . url('/admin/posts/index.php?type=' . $type)); exit; }

$stmt = $pdo->prepare("DELETE FROM posts WHERE id=:id AND type=:type");
$stmt->execute([':id'=>$id, ':type'=>$type]);

header('Location: ' . url('/admin/posts/index.php?type=' . $type));
exit;