<?php
declare(strict_types=1);
function get_village_page(PDO $pdo, string $slug): ?array {
    $stmt = $pdo->prepare("SELECT id, slug, title, content, created_at, updated_at FROM village_pages WHERE slug = :slug LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $row = $stmt->fetch();
    return $row ?: null;
}
function get_setting(PDO $pdo, string $key): ?string {
    $stmt = $pdo->prepare("SELECT value FROM settings WHERE `key` = :k LIMIT 1");
    $stmt->execute([':k' => $key]);
    $row = $stmt->fetch();
    return $row ? (string)$row['value'] : null;
}
function set_setting(PDO $pdo, string $key, ?string $value): void {
    $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`) VALUES (:k, :v)
                           ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)");
    $stmt->execute([':k' => $key, ':v' => $value]);
}
