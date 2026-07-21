<?php

declare(strict_types=1);

require_once __DIR__ . '/handlers.php';

try {
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $connection = getDbConnection();

    if ($method === 'POST') {
        $payload = readJsonBody();
        $errors = validateRegistrationPayload($payload);
        if ($errors) {
            sendJson(['success' => false, 'errors' => $errors], 422);
        }

        $user = createUser($connection, $payload);
        sendJson(['success' => true, 'message' => 'User created successfully.', 'user' => $user], 201);
    }

    if ($method === 'GET') {
        $email = trim((string) ($_GET['email'] ?? ''));
        if ($email === '') {
            $users = getAllUsers($connection);
            foreach ($users as &$user) {
                unset($user['password_hash']);
            }
            sendJson(['success' => true, 'users' => $users]);
        }

        $user = findUserByEmail($connection, $email);
        if ($user === null) {
            sendJson(['success' => false, 'errors' => ['User not found.']], 404);
        }

        unset($user['password_hash']);
        sendJson(['success' => true, 'user' => $user]);
    }

    if ($method === 'PUT') {
        $payload = readJsonBody();
        $errors = validateUpdatePayload($payload);
        if ($errors) {
            sendJson(['success' => false, 'errors' => $errors], 422);
        }

        $email = trim((string) ($payload['email'] ?? ''));
        $updatedUser = updateUser($connection, $email, $payload);
        unset($updatedUser['password_hash']);
        sendJson(['success' => true, 'message' => 'User updated successfully.', 'user' => $updatedUser]);
    }

    sendJson(['success' => false, 'errors' => ['Unsupported method.']], 405);
} catch (InvalidArgumentException $exception) {
    sendJson(['success' => false, 'errors' => [$exception->getMessage()]], 400);
} catch (mysqli_sql_exception $exception) {
    sendJson(['success' => false, 'errors' => ['Database error: ' . $exception->getMessage()]], 500);
} catch (RuntimeException $exception) {
    sendJson(['success' => false, 'errors' => [$exception->getMessage()]], 422);
}
