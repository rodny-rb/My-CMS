<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php#contact');
    exit;
}

verify_csrf();

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$message = trim((string)($_POST['message'] ?? ''));

if (
    $name === '' || mb_strlen($name) > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 190 ||
    mb_strlen($subject) > 190 ||
    $message === '' || mb_strlen($message) > 5000
) {
    http_response_code(422);
    exit('Please check your form fields and try again.');
}

if (!table_exists($pdo, 'messages')) {
    error_log('Contact form failed: messages table does not exist.');
    http_response_code(503);
    exit('The contact form is temporarily unavailable.');
}

$fields = [
    'name' => $name,
    'email' => $email,
    'subject' => $subject,
    'message' => $message,
];

if (column_exists($pdo, 'messages', 'created_at')) {
    // Let the database default set created_at if it has one.
}

try {
    $insertFields = [];
    $values = [];

    foreach ($fields as $field => $value) {
        if (column_exists($pdo, 'messages', $field)) {
            $insertFields[] = $field;
            $values[] = $value;
        }
    }

    if (!in_array('name', $insertFields, true) ||
        !in_array('email', $insertFields, true) ||
        !in_array('message', $insertFields, true)) {
        throw new RuntimeException('The messages table does not have the required contact fields.');
    }

    if (column_exists($pdo, 'messages', 'read_status')) {
        $insertFields[] = 'read_status';
        $values[] = 0;
    } elseif (column_exists($pdo, 'messages', 'is_read')) {
        $insertFields[] = 'is_read';
        $values[] = 0;
    }

    $quotedFields = array_map(static fn($field) => '`' . $field . '`', $insertFields);
    $placeholders = implode(', ', array_fill(0, count($insertFields), '?'));

    $sql = 'INSERT INTO `messages` (' . implode(', ', $quotedFields) . ') VALUES (' . $placeholders . ')';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($values);

    header('Location: index.php?sent=1#contact');
    exit;
} catch (Throwable $e) {
    error_log('Contact form insert failed: ' . $e->getMessage());
    http_response_code(500);
    exit('We could not send your message. Please try again later.');
}
