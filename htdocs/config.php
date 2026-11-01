<?php
declare(strict_types=1);

/*
 * Rodny Portfolio CMS
 * PHP 8.x + PDO + MySQL
 * Replace the placeholder values with your current hosting credentials.
 */

ini_set('display_errors', '0');
ini_set('log_errors', '1');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_name('rodny_cms_session');
    session_set_cookie_params([
        'httponly' => true,
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'samesite' => 'Lax',
        'path'     => '/',
    ]);
    session_start();
}

$dbHost = 'YOUR_CURRENT_MYSQL_HOST';
$dbName = 'YOUR_CURRENT_DATABASE_NAME';
$dbUser = 'YOUR_CURRENT_DATABASE_USERNAME';
$dbPass = 'YOUR_NEW_DATABASE_PASSWORD';

try {
    $pdo = new PDO(
        "mysql:host={$dbHost};dbname={$dbName};charset=utf8mb4",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );

    // Backwards compatibility with existing files that use $conn.
    $conn = $pdo;
} catch (Throwable $e) {
    error_log('Rodny CMS database connection failed: ' . $e->getMessage());
    http_response_code(503);
    exit('The portfolio is temporarily unavailable. Please try again later.');
}

function e(mixed $value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    $sent = (string)($_POST['csrf_token'] ?? '');
    $saved = (string)($_SESSION['csrf_token'] ?? '');

    if ($saved === '' || $sent === '' || !hash_equals($saved, $sent)) {
        http_response_code(403);
        exit('Security token invalid. Reload the page and try again.');
    }
}

function table_exists(PDO $pdo, string $table): bool
{
    // Table names must be fixed application constants, never raw user input.
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.tables
         WHERE table_schema = DATABASE() AND table_name = ?'
    );
    $stmt->execute([$table]);
    return (int)$stmt->fetchColumn() > 0;
}

function column_exists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare(
        'SELECT COUNT(*) FROM information_schema.columns
         WHERE table_schema = DATABASE()
           AND table_name = ? AND column_name = ?'
    );
    $stmt->execute([$table, $column]);
    return (int)$stmt->fetchColumn() > 0;
}

function first_column(PDO $pdo, string $table, array $candidates): ?string
{
    foreach ($candidates as $column) {
        if (column_exists($pdo, $table, $column)) {
            return $column;
        }
    }
    return null;
}

function safe_rows(PDO $pdo, string $table, string $orderColumn = 'id', int $limit = 50): array
{
    $allowedTables = ['projects', 'skills', 'messages', 'site_banner'];
    if (!in_array($table, $allowedTables, true) || !table_exists($pdo, $table)) {
        return [];
    }

    if (!column_exists($pdo, $table, $orderColumn)) {
        $orderColumn = first_column($pdo, $table, ['id', 'created_at']) ?? '';
    }

    // Table/column names come only from the allow-list and schema inspection.
    $sql = 'SELECT * FROM `' . $table . '`';
    if ($orderColumn !== '') {
        $sql .= ' ORDER BY `' . $orderColumn . '` DESC';
    }
    $sql .= ' LIMIT ' . max(1, min(100, $limit));

    try {
        return $pdo->query($sql)->fetchAll();
    } catch (Throwable $e) {
        error_log('Rodny CMS query failed for ' . $table . ': ' . $e->getMessage());
        return [];
    }
}
