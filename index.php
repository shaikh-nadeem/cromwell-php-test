<?php
session_start();
require_once __DIR__ . '/config.php';
$baseUrl = rtrim($config['app']['base_url'], '/');
if (!empty($_SESSION['logged_in'])) {
    header('Location: ' . $baseUrl . '/user/home');
    exit;
}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cromwell | PHP Test</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="display-6 fw-bold mb-3"><?= htmlspecialchars($config['app']['name']) ?></h1>
                        <p class="lead text-muted">A secure user registration, login, profile editing, and user management.</p>
                        <div class="d-flex flex-wrap gap-2 mt-4">
                            <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl) ?>/user/registration">Create Account</a>
                            <a class="btn btn-outline-secondary" href="<?= htmlspecialchars($baseUrl) ?>/user/login">Sign In</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
