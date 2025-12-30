<?php
require_once __DIR__ . '/../config/app.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($title ?? APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= url('/assets/css/style.css') ?>?v=<?= time() ?>">

</head>
<body>

<?php include __DIR__ . '/flash.php'; ?>
