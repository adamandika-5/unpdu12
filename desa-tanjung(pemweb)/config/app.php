<?php
declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('APP_NAME', 'Desa Tanjung');
define('BASE_PATH', dirname(__DIR__)); 

$projectDir = '/' . basename(BASE_PATH);
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseUrl = '';

if (is_string($scriptName) && $scriptName !== '' && $projectDir !== '/') {
    $pos = strpos($scriptName, $projectDir);
    if ($pos !== false) {
        $baseUrl = substr($scriptName, 0, $pos + strlen($projectDir));
    }
}

if ($baseUrl === '/') $baseUrl = '';
define('BASE_URL', $baseUrl);

define('UPLOAD_DIR', BASE_PATH . '/uploads');

function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function url(string $path): string {
    $path = '/' . ltrim($path, '/');
    return BASE_URL . $path;
}


function str_limit(string $text, int $max = 80, string $suffix = '...'): string {
    $text = trim($text);
    if ($text === '') return '';
    if (function_exists('mb_strlen') && function_exists('mb_substr')) {
        if (mb_strlen($text, 'UTF-8') <= $max) return $text;
        return mb_substr($text, 0, $max, 'UTF-8') . $suffix;
    }
    if (strlen($text) <= $max) return $text;
    return substr($text, 0, $max) . $suffix;
}
function is_logged_in(): bool {
    return isset($_SESSION['user']) && is_array($_SESSION['user']);
}

function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

function is_admin(): bool {
    return is_logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: ' . url('/auth/login.php'));
        exit;
    }
}

function require_admin(): void {
    require_login();
    if (!is_admin()) {
        http_response_code(403);
        echo "<h3 style='font-family:sans-serif'>Akses ditolak (Admin saja).</h3>";
        exit;
    }
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return (string)$_SESSION['csrf_token'];
}

function csrf_validate(?string $token): bool {
    if (!is_string($token) || $token === '') return false;
    return hash_equals((string)($_SESSION['csrf_token'] ?? ''), $token);
}

function flash_set(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_get_all(): array {
    $items = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return is_array($items) ? $items : [];
}

function upload_image(array $file, int $maxBytes = 100000000): ?string {
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) return null;

    if (($file['size'] ?? 0) > $maxBytes) {
        throw new RuntimeException('Ukuran file terlalu besar. Maks 2MB.');
    }

    $tmp = $file['tmp_name'] ?? '';
    $original = $file['name'] ?? '';
    $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

    $allowed = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array($ext, $allowed, true)) {
        throw new RuntimeException('Format file tidak didukung. Gunakan jpg/jpeg/png/webp.');
    }

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0755, true);
    }

    $safeName = preg_replace('/[^a-zA-Z0-9_-]+/', '-', pathinfo($original, PATHINFO_FILENAME));
    $safeName = trim((string)$safeName, '-');
    if ($safeName === '') $safeName = 'file';

    $filename = $safeName . '-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
    $dest = UPLOAD_DIR . '/' . $filename;

    if (!move_uploaded_file($tmp, $dest)) {
        throw new RuntimeException('Gagal upload file.');
    }

    return 'uploads/' . $filename;
}
