<?php
session_start();
require_once __DIR__ . '/../config.php';
$baseUrl = rtrim($config['app']['base_url'], '/');
if (empty($_SESSION['logged_in'])) {
    header('Location: ' . $baseUrl . '/user/login');
    exit;
}

$currentEmail = (string) ($_SESSION['user']['email'] ?? '');
$users = [];
$context = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Content-Type: application/json\r\n",
        'ignore_errors' => true,
    ],
]);
$response = @file_get_contents($baseUrl . '/api/user', false, $context);
if ($response !== false) {
    $decoded = json_decode($response, true);
    if (!empty($decoded['success']) && !empty($decoded['users'])) {
        $users = $decoded['users'];
    }
}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | PHP Test</title>
    
    <?php
    $emojis = [
        "1f600", // 😀
        "1f680", // 🚀
        "1f525", // 🔥
        "1f389", // 🎉
        "1f4bb", // 💻
        "1f984", // 🦄
        "1f916", // 🤖
        "1f47d", // 👽
    ];

    $emoji = $emojis[array_rand($emojis)];
    ?>

    <link rel="icon" href="https://cdnjs.cloudflare.com/ajax/libs/twemoji/14.0.2/72x72/<?php echo $emoji; ?>.png">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --bs-primary: #0d6efd;
            --bs-secondary: #6c757d;
        }
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
        .navbar-custom {
            background: linear-gradient(to right, #0d6efd, #0dcaf0);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08) !important;
            border: none;
            overflow: hidden;
        }
        .card-header {
            background: linear-gradient(to right, #f8f9fa, #f1f3f5);
            border-bottom: 2px solid #e9ecef;
            padding: 1.5rem;
        }
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.65rem 1.5rem;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: linear-gradient(to right, #0d6efd, #0dcaf0);
            border: none;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
        }
        .btn-outline-primary:hover {
            transform: translateY(-2px);
        }
        .btn-outline-danger:hover {
            transform: translateY(-2px);
        }
        .table {
            margin-bottom: 0;
        }
        .table thead {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        .table tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table-responsive {
            border-radius: 8px;
        }
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .header-actions .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }
        .welcome-section {
            margin-bottom: 2rem;
        }
        .welcome-title {
            color: #2c3e50;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .user-count {
            padding: 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            color: white;
            text-align: center;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .user-count-number {
            font-size: 2.5rem;
            font-weight: 700;
            display: block;
        }
        .user-count-label {
            font-size: 0.95rem;
            opacity: 0.9;
        }
        @media (max-width: 768px) {
            .header-actions {
                width: 100%;
                margin-top: 1rem;
            }
            .welcome-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container-lg">
            <span class="navbar-brand fw-bold">
                <i class="bi bi-shield-check"></i> <?= htmlspecialchars($config['app']['name']) ?>
            </span>
            <div class="ms-auto">
                <span class="navbar-text text-light me-3">
                    <i class="bi bi-person-circle"></i> <?= htmlspecialchars($currentEmail) ?>
                </span>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-lg py-5">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="d-flex justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="welcome-title mb-2">
                        <i class="bi bi-house-door-fill text-primary"></i> Dashboard
                    </h1>
                    <p class="text-muted mb-0">Manage your account and review registered users</p>
                </div>
                <div class="header-actions">
                    <a class="btn btn-primary" href="<?= htmlspecialchars($baseUrl) ?>/user/edit">
                        <i class="bi bi-pencil-square"></i> Edit Profile
                    </a>
                    <a class="btn btn-outline-danger" href="<?= htmlspecialchars($baseUrl) ?>/user/logout">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="user-count">
                    <span class="user-count-number"><?= count($users) ?></span>
                    <span class="user-count-label">Total Registered Users</span>
                </div>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="card">
            <div class="card-header">
                <h2 class="h5 mb-0">
                    <i class="bi bi-people-fill text-primary"></i> Registered Users
                </h2>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>
                                    <i class="bi bi-person"></i> Name
                                </th>
                                <th>
                                    <i class="bi bi-envelope"></i> Email
                                </th>
                                <th>
                                    <i class="bi bi-telephone"></i> Mobile
                                </th>
                                <th>
                                    <i class="bi bi-calendar-event"></i> Registered
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($users)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox display-6 text-muted d-block mb-2"></i>
                                        <p class="text-muted mb-0">No users registered yet</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="avatar" style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 0.9rem;">
                                                    <?= strtoupper(substr(($user['forenames'] ?? 'U')[0], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <div class="fw-600"><?= htmlspecialchars(trim(($user['forenames'] ?? '') . ' ' . ($user['surname'] ?? ''))) ?></div>
                                                    <div class="text-muted small"><?= htmlspecialchars(($user['title'] ?? '') ?? '') ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                <?= htmlspecialchars((string) ($user['email'] ?? '')) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <i class="bi bi-telephone-inbound text-success"></i>
                                            <?= htmlspecialchars((string) ($user['phone_mobile'] ?? 'N/A')) ?>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <?= htmlspecialchars((string) ($user['created_at'] ?? 'N/A')) ?>
                                            </small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
