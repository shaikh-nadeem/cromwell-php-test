<?php

declare(strict_types=1);

require_once __DIR__ . '/../config.php';

function sendJson(array $payload, int $statusCode = 200): void
{
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($payload, JSON_UNESCAPED_SLASHES);
    exit;
}

function readJsonBody(): array
{
    $rawBody = file_get_contents('php://input');
    if ($rawBody === false || trim($rawBody) === '') {
        return [];
    }

    $decoded = json_decode($rawBody, true);
    if (!is_array($decoded)) {
        throw new InvalidArgumentException('The request body must be valid JSON.');
    }

    return $decoded;
}

function validateRegistrationPayload(array $data): array
{
    $errors = [];

    $required = ['forenames', 'surname', 'title', 'date_of_birth', 'phone_mobile', 'email', 'password', 'password_confirmation'];
    foreach ($required as $field) {
        if (!isset($data[$field]) || trim((string) $data[$field]) === '') {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
        }
    }

    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address must be valid.';
    }

    if (!empty($data['password']) && strlen((string) $data['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    if (!empty($data['password']) && !empty($data['password_confirmation']) && (string) $data['password'] !== (string) $data['password_confirmation']) {
        $errors[] = 'Passwords do not match.';
    }

    if (!empty($data['date_of_birth']) && !validateDateString((string) $data['date_of_birth'])) {
        $errors[] = 'Date of birth must be a valid date in YYYY-MM-DD format.';
    }

    if (!empty($data['phone_mobile']) && !preg_match('/^\+?[0-9\s()-]{7,20}$/', (string) $data['phone_mobile'])) {
        $errors[] = 'Mobile phone number must contain only digits, spaces, parentheses, hyphens or a leading +.';
    }

    return $errors;
}

function validateLoginPayload(array $data): array
{
    $errors = [];

    if (!isset($data['email']) || trim((string) $data['email']) === '') {
        $errors[] = 'Email is required.';
    }

    if (!isset($data['password']) || trim((string) $data['password']) === '') {
        $errors[] = 'Password is required.';
    }

    if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address must be valid.';
    }

    return $errors;
}

function validateUpdatePayload(array $data): array
{
    $errors = [];

    if (!isset($data['email']) || trim((string) $data['email']) === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email address must be valid.';
    }

    $stringFields = ['title', 'forenames', 'surname'];
    foreach ($stringFields as $field) {
        if (array_key_exists($field, $data) && trim((string) $data[$field]) === '') {
            $errors[] = ucfirst($field) . ' cannot be empty.';
        }
    }

    if (array_key_exists('date_of_birth', $data) && trim((string) $data['date_of_birth']) !== '' && !validateDateString((string) $data['date_of_birth'])) {
        $errors[] = 'Date of birth must be a valid date in YYYY-MM-DD format.';
    }

    if (array_key_exists('phone_mobile', $data) && trim((string) $data['phone_mobile']) !== '' && !preg_match('/^\+?[0-9\s()-]{7,20}$/', (string) $data['phone_mobile'])) {
        $errors[] = 'Mobile phone number must contain only digits, spaces, parentheses, hyphens or a leading +.';
    }

    if (array_key_exists('phone_other', $data) && trim((string) $data['phone_other']) !== '' && !preg_match('/^\+?[0-9\s()-]{7,20}$/', (string) $data['phone_other'])) {
        $errors[] = 'Other phone number must contain only digits, spaces, parentheses, hyphens or a leading +.';
    }

    if (array_key_exists('password', $data) && trim((string) $data['password']) !== '' && strlen((string) $data['password']) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }

    return $errors;
}

function validateDateString(string $value): bool
{
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    return $date instanceof DateTimeImmutable && $date->format('Y-m-d') === $value;
}

function ensureUserTable(mysqli $connection): void
{
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(20) NOT NULL,
        forenames VARCHAR(100) NOT NULL,
        surname VARCHAR(100) NOT NULL,
        date_of_birth DATE NOT NULL,
        phone_mobile VARCHAR(20) NOT NULL,
        phone_other VARCHAR(20),
        email VARCHAR(255) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if (!$connection->query($sql)) {
        throw new RuntimeException('Unable to create users table: ' . $connection->error);
    }
}

function findUserByEmail(mysqli $connection, string $email): ?array
{
    $email = strtolower(trim($email));
    $stmt = $connection->prepare('SELECT id, title, forenames, surname, date_of_birth, phone_mobile, phone_other, email, password_hash, created_at, updated_at FROM users WHERE email = ? LIMIT 1');
    if ($stmt === false) {
        throw new RuntimeException('Unable to prepare user lookup statement: ' . $connection->error);
    }

    $stmt->bind_param('s', $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();

    return $row ?: null;
}

function getAllUsers(mysqli $connection): array
{
    ensureUserTable($connection);

    $result = $connection->query('SELECT id, title, forenames, surname, date_of_birth, phone_mobile, phone_other, email, created_at, updated_at FROM users ORDER BY created_at DESC');
    if ($result === false) {
        throw new RuntimeException('Unable to fetch users: ' . $connection->error);
    }

    $rows = [];
    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    return $rows;
}

function createUser(mysqli $connection, array $data): array
{
    ensureUserTable($connection);

    $email = strtolower(trim((string) $data['email']));
    if (findUserByEmail($connection, $email) !== null) {
        throw new RuntimeException('A user with that email already exists.');
    }

    $statement = $connection->prepare(
        'INSERT INTO users (title, forenames, surname, date_of_birth, phone_mobile, phone_other, email, password_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
    );
    if ($statement === false) {
        throw new RuntimeException('Unable to prepare insert statement: ' . $connection->error);
    }

    $passwordHash = password_hash((string) $data['password'], PASSWORD_DEFAULT);
    $title = trim((string) $data['title']);
    $forenames = trim((string) $data['forenames']);
    $surname = trim((string) $data['surname']);
    $dateOfBirth = trim((string) $data['date_of_birth']);
    $phoneMobile = trim((string) $data['phone_mobile']);
    $phoneOther = trim((string) ($data['phone_other'] ?? ''));

    $statement->bind_param(
        'ssssssss',
        $title,
        $forenames,
        $surname,
        $dateOfBirth,
        $phoneMobile,
        $phoneOther,
        $email,
        $passwordHash
    );

    if (!$statement->execute()) {
        throw new RuntimeException('Unable to create user: ' . $statement->error);
    }

    $id = $connection->insert_id;

    return [
        'id' => (int) $id,
        'title' => trim((string) $data['title']),
        'forenames' => trim((string) $data['forenames']),
        'surname' => trim((string) $data['surname']),
        'date_of_birth' => trim((string) $data['date_of_birth']),
        'phone_mobile' => trim((string) $data['phone_mobile']),
        'phone_other' => trim((string) ($data['phone_other'] ?? '')),
        'email' => $email,
    ];
}

function authenticateUser(mysqli $connection, string $email, string $password): ?array
{
    ensureUserTable($connection);

    $user = findUserByEmail($connection, $email);
    if ($user === null) {
        return null;
    }

    if (!password_verify($password, (string) $user['password_hash'])) {
        return null;
    }

    unset($user['password_hash']);
    return $user;
}

function updateUser(mysqli $connection, string $email, array $data): array
{
    ensureUserTable($connection);

    $existingUser = findUserByEmail($connection, $email);
    if ($existingUser === null) {
        throw new RuntimeException('User not found.');
    }

    $fields = [];
    $params = [];
    $types = '';

    $map = [
        'title' => 'title',
        'forenames' => 'forenames',
        'surname' => 'surname',
        'date_of_birth' => 'date_of_birth',
        'phone_mobile' => 'phone_mobile',
        'phone_other' => 'phone_other',
    ];

    foreach ($map as $inputKey => $column) {
        if (array_key_exists($inputKey, $data)) {
            $fields[] = $column . ' = ?';
            $value = trim((string) $data[$inputKey]);
            $params[] = $value;
            $types .= 's';
        }
    }

    if (array_key_exists('password', $data) && trim((string) $data['password']) !== '') {
        $fields[] = 'password_hash = ?';
        $value = password_hash((string) $data['password'], PASSWORD_DEFAULT);
        $params[] = $value;
        $types .= 's';
    }

    if (count($fields) === 0) {
        return $existingUser;
    }

    $fields[] = 'updated_at = CURRENT_TIMESTAMP';
    $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE email = ?';
    $value = strtolower(trim($email));
    $params[] = $value;
    $types .= 's';

    $stmt = $connection->prepare($sql);
    if ($stmt === false) {
        throw new RuntimeException('Unable to prepare update statement: ' . $connection->error);
    }

    $bindValues = [];
    foreach ($params as $index => $param) {
        $bindValues[$index] = &$params[$index];
    }

    $stmt->bind_param($types, ...$bindValues);
    if (!$stmt->execute()) {
        throw new RuntimeException('Unable to update user: ' . $stmt->error);
    }

    return findUserByEmail($connection, $email) ?? $existingUser;
}
