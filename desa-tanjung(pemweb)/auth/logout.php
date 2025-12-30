<?php
require_once __DIR__ . '/../config/app.php';

session_destroy();

session_start();
flash_set('success', 'Anda telah logout.');

header('Location: ' . url('/index.php'));
exit;
