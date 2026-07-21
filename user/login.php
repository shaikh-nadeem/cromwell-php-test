<?php
session_start();
require_once __DIR__ . '/../config.php';
$baseUrl = rtrim($config['app']['base_url'], '/');

function callApiJson(string $url, array $data = [], string $method = 'POST'): array
{
    $options = [
        'http' => [
            'method' => $method,
            'header' => "Content-Type: application/json\r\n",
            'content' => json_encode($data),
            'ignore_errors' => true,
        ],
    ];

    $context = stream_context_create($options);
    $response = @file_get_contents($url, false, $context);
    if ($response === false) {
        return ['success' => false, 'errors' => ['Unable to reach the authentication service.']];
    }

    $decoded = json_decode($response, true);
    return is_array($decoded) ? $decoded : ['success' => false, 'errors' => ['Invalid response from authentication service.']];
}

$message = '';
$messageType = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payload = [
        'email' => trim((string) ($_POST['email'] ?? '')),
        'password' => (string) ($_POST['password'] ?? ''),
    ];

    $result = callApiJson($baseUrl . '/api/login', $payload, 'POST');
    if (!empty($result['success'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user'] = $result['user'] ?? [];
        $_SESSION['user']['email'] = strtolower((string) ($result['user']['email'] ?? $payload['email']));
        session_regenerate_id(true);
        header('Location: ' . $baseUrl . '/user/home');
        exit;
    }

    $message = implode(' ', (array) ($result['errors'] ?? ['Login failed.']));
    $messageType = 'danger';
}
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | PHP Test</title>
    <!-- Favicon -->
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
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.1) !important;
            border: none;
            overflow: hidden;
        }
        .card-body {
            padding: 3rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.6rem;
        }
        .btn {
            border-radius: 8px;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
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
        .alert {
            border-radius: 8px;
            border: none;
            animation: slideDown 0.3s ease;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        h1 {
            color: #2c3e50;
            font-weight: 700;
        }
        .text-muted {
            color: #7f8c8d !important;
        }
        .register-link {
            text-decoration: none;
            color: #0d6efd;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        .register-link:hover {
            color: #0dcaf0;
        }
        .password-toggle-group {
            position: relative;
        }
        .password-toggle-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s ease;
            background: none;
            border: none;
            padding: 0;
            font-size: 1.1rem;
            line-height: 1;
            z-index: 10;
        }
        .password-toggle-icon:hover {
            color: #0d6efd;
        }
        .password-field-with-toggle {
            padding-right: 45px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <i class="bi bi-shield-check display-4 text-primary"></i>
                        </div>
                        <h1 class="h3 text-center mb-2">Sign In</h1>
                        <p class="text-muted text-center mb-4">Access your account securely</p>
                        
                        <?php if ($message !== ''): ?>
                            <div class="alert alert-<?= htmlspecialchars($messageType) ?>" role="alert">
                                <i class="bi bi-<?= $messageType === 'danger' ? 'exclamation-circle' : 'check-circle' ?>"></i>
                                <?= htmlspecialchars($message) ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="post" class="row g-3">
                            <div class="col-12">
                                <label for="email" class="form-label">Email Address</label>
                                <input id="email" name="email" type="email" class="form-control form-control-lg" 
                                       placeholder="Enter your email" required autofocus>
                            </div>
                            <div class="col-12">
                                <label for="password" class="form-label">Password</label>
                                <div class="password-toggle-group">
                                    <input id="password" name="password" type="password" class="form-control form-control-lg password-field-with-toggle" 
                                           placeholder="Enter your password" required>
                                    <button type="button" class="password-toggle-icon" data-target="password" title="Toggle password visibility">
                                        <i class="bi bi-eye-slash"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 btn-lg">
                                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                                </button>
                            </div>
                        </form>
                        
                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">
                                Don't have an account? 
                                <a href="<?= htmlspecialchars($baseUrl) ?>/user/registration" class="register-link">Register here</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password visibility toggle
        document.querySelectorAll('.password-toggle-icon').forEach(btn => {
            btn.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const field = document.getElementById(targetId);
                const isPassword = field.type === 'password';
                
                field.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
                this.title = isPassword ? 'Hide password' : 'Show password';
            });
        });
    </script>
</body>
</html>
