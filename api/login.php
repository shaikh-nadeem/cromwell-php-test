<?php

declare(strict_types=1);

require_once __DIR__ . '/handlers.php';

try {
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
        sendJson(['success' => false, 'errors' => ['Only POST requests are supported.']], 405);
    }

    $payload = readJsonBody();
    $errors = validateLoginPayload($payload);
    if ($errors) {
        sendJson(['success' => false, 'errors' => $errors], 422);
    }

    $connection = getDbConnection();
    $user = authenticateUser($connection, (string) $payload['email'], (string) $payload['password']);
    if ($user === null) {
        sendJson(['success' => false, 'errors' => ['Invalid email or password.']], 401);
    }

    sendJson(['success' => true, 'message' => 'Login successful.', 'user' => $user]);
} catch (InvalidArgumentException $exception) {
    sendJson(['success' => false, 'errors' => [$exception->getMessage()]], 400);
} catch (mysqli_sql_exception $exception) {
    sendJson(['success' => false, 'errors' => ['Database error: ' . $exception->getMessage()]], 500);
} catch (RuntimeException $exception) {
    sendJson(['success' => false, 'errors' => [$exception->getMessage()]], 422);
}
