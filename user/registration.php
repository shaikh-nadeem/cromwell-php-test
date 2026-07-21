<?php
session_start();
require_once __DIR__ . '/../config.php';
$baseUrl = rtrim($config['app']['base_url'], '/');
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register | PHP Test</title>

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
            padding: 2rem;
        }
        .form-control, .form-select {
            border-radius: 8px;
            border: 1.5px solid #dee2e6;
            padding: 0.7rem 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
        }
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        .form-control.is-valid {
            border-color: #198754;
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
        .form-section {
            margin-bottom: 2rem;
        }
        .form-section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #0d6efd;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.6rem;
            font-size: 0.95rem;
        }
        .password-strength {
            margin-top: 0.5rem;
            font-size: 0.85rem;
        }
        .password-strength-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0;
        }
        .password-strength-item.valid {
            color: #198754;
        }
        .password-strength-item.invalid {
            color: #dc3545;
        }
        .strength-icon {
            font-size: 0.75rem;
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
        .header-actions {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
        }
        .header-actions .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
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
                <a href="<?= htmlspecialchars($baseUrl) ?>/user/login" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Sign In
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-lg py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Card Header -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h1 class="h2 mb-2 fw-bold">
                                    <i class="bi bi-person-plus text-primary"></i> Create Your Account
                                </h1>
                                <p class="text-muted mb-0">Register with your personal details to get started</p>
                            </div>
                            <a class="btn btn-outline-secondary" href="<?= htmlspecialchars($baseUrl) ?>/user/login">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Message Alert -->
                <div id="message" class="alert d-none" role="alert">
                    <div id="messageContent"></div>
                </div>

                <!-- Registration Form -->
                <div class="card">
                    <div class="card-body p-4">
                        <form id="registration-form" class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-person"></i> Personal Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="title" class="form-label">Title</label>
                                        <select id="title" name="title" class="form-select" required>
                                            <option value="">Select</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Miss">Miss</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Dr">Dr</option>
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="forenames" class="form-label">First Name(s)</label>
                                        <input id="forenames" name="forenames" type="text" class="form-control" 
                                               placeholder="e.g., John" required>
                                    </div>
                                    <div class="col-md-5">
                                        <label for="surname" class="form-label">Last Name</label>
                                        <input id="surname" name="surname" type="text" class="form-control" 
                                               placeholder="e.g., Smith" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-telephone"></i> Contact Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input id="date_of_birth" name="date_of_birth" type="date" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_mobile" class="form-label">Mobile Phone</label>
                                        <input id="phone_mobile" name="phone_mobile" type="tel" class="form-control" 
                                               placeholder="e.g., +1 (555) 123-4567" required>
                                    </div>
                                    <div class="col-12">
                                        <label for="phone_other" class="form-label">Other Phone (Optional)</label>
                                        <input id="phone_other" name="phone_other" type="tel" class="form-control" 
                                               placeholder="e.g., +1 (555) 987-6543">
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-envelope"></i> Account Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input id="email" name="email" type="email" class="form-control" 
                                               placeholder="your.email@example.com" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Security -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-lock"></i> Security
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="password-toggle-group">
                                            <input id="password" name="password" type="password" class="form-control password-field-with-toggle" 
                                                   placeholder="Create a strong password" required>
                                            <button type="button" class="password-toggle-icon" data-target="password" title="Toggle password visibility">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength" id="passwordStrength"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                                        <div class="password-toggle-group">
                                            <input id="password_confirmation" name="password_confirmation" type="password" 
                                                   class="form-control password-field-with-toggle" placeholder="Re-enter your password" required>
                                            <button type="button" class="password-toggle-icon" data-target="password_confirmation" title="Toggle password visibility">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div id="passwordMatch" class="mt-2"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 pt-3 border-top">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="bi bi-check-circle"></i> Create Account
                                </button>
                                <p class="text-muted text-center mt-3 mb-0">
                                    Already have an account? 
                                    <a href="<?= htmlspecialchars($baseUrl) ?>/user/login" style="color: #0d6efd; font-weight: 600;">Sign In</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = <?= json_encode($baseUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&^#()_+=\-\[\]{};:'",.<>?\/\\|`]).{8,}$/;

        const passwordField = document.getElementById('password');
        const passwordConfirmField = document.getElementById('password_confirmation');
        const strengthDiv = document.getElementById('passwordStrength');
        const matchDiv = document.getElementById('passwordMatch');
        const form = document.getElementById('registration-form');
        const message = document.getElementById('message');

        // Password visibility toggle
        document.querySelectorAll('.password-toggle-icon').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.dataset.target;
                const field = document.getElementById(targetId);
                const isPassword = field.type === 'password';
                
                field.type = isPassword ? 'text' : 'password';
                this.innerHTML = isPassword ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
                this.title = isPassword ? 'Hide password' : 'Show password';
            });
        });

        function validatePasswordStrength(password) {
            const checks = {
                'length': password.length >= 8,
                'uppercase': /[A-Z]/.test(password),
                'lowercase': /[a-z]/.test(password),
                'number': /\d/.test(password),
                'special': /[@$!%*?&^#()_+=\-\[\]{};:'",.<>?\/\\|`]/.test(password),
            };

            const allValid = Object.values(checks).every(v => v);
            
            const html = `
                <div class="password-strength-item ${checks.length ? 'valid' : 'invalid'}">
                    <i class="bi bi-${checks.length ? 'check-circle-fill' : 'circle'} strength-icon"></i>
                    <span>At least 8 characters</span>
                </div>
                <div class="password-strength-item ${checks.uppercase ? 'valid' : 'invalid'}">
                    <i class="bi bi-${checks.uppercase ? 'check-circle-fill' : 'circle'} strength-icon"></i>
                    <span>Uppercase letter (A-Z)</span>
                </div>
                <div class="password-strength-item ${checks.lowercase ? 'valid' : 'invalid'}">
                    <i class="bi bi-${checks.lowercase ? 'check-circle-fill' : 'circle'} strength-icon"></i>
                    <span>Lowercase letter (a-z)</span>
                </div>
                <div class="password-strength-item ${checks.number ? 'valid' : 'invalid'}">
                    <i class="bi bi-${checks.number ? 'check-circle-fill' : 'circle'} strength-icon"></i>
                    <span>Number (0-9)</span>
                </div>
                <div class="password-strength-item ${checks.special ? 'valid' : 'invalid'}">
                    <i class="bi bi-${checks.special ? 'check-circle-fill' : 'circle'} strength-icon"></i>
                    <span>Special character (!@#$%^&...)</span>
                </div>
            `;

            strengthDiv.innerHTML = html;
            passwordField.classList.toggle('is-valid', allValid && password.length > 0);
            passwordField.classList.toggle('is-invalid', !allValid && password.length > 0);

            return allValid;
        }

        function validatePasswordMatch() {
            const match = passwordField.value === passwordConfirmField.value && passwordField.value.length > 0;
            const isValid = validatePasswordStrength(passwordField.value);
            
            if (passwordConfirmField.value.length > 0) {
                if (match && isValid) {
                    matchDiv.innerHTML = '<div class="password-strength-item valid"><i class="bi bi-check-circle-fill strength-icon"></i> Passwords match</div>';
                    passwordConfirmField.classList.add('is-valid');
                    passwordConfirmField.classList.remove('is-invalid');
                } else if (!match) {
                    matchDiv.innerHTML = '<div class="password-strength-item invalid"><i class="bi bi-circle strength-icon"></i> Passwords do not match</div>';
                    passwordConfirmField.classList.add('is-invalid');
                    passwordConfirmField.classList.remove('is-valid');
                }
            } else {
                matchDiv.innerHTML = '';
                passwordConfirmField.classList.remove('is-valid', 'is-invalid');
            }
        }

        passwordField.addEventListener('input', () => {
            validatePasswordStrength(passwordField.value);
            validatePasswordMatch();
        });

        passwordConfirmField.addEventListener('input', validatePasswordMatch);

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            const payload = Object.fromEntries(new FormData(form).entries());

            if (!validatePasswordStrength(payload.password)) {
                showMessage('Password does not meet all requirements.', 'danger');
                return;
            }

            if (payload.password !== payload.password_confirmation) {
                showMessage('Passwords do not match.', 'danger');
                return;
            }

            try {
                const response = await fetch(baseUrl + '/api/user', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await response.json();
                
                if (data.success) {
                    showMessage('✓ Registration successful! Redirecting to login...', 'success');
                    form.reset();
                    strengthDiv.innerHTML = '';
                    matchDiv.innerHTML = '';
                    setTimeout(() => {
                        window.location.href = baseUrl + '/user/login';
                    }, 2000);
                } else {
                    showMessage(data.errors || ['Registration failed.'], 'danger');
                }
            } catch (error) {
                showMessage('Connection error: ' + error.message, 'danger');
            }
        });

        function showMessage(text, type = 'info') {
            const messageContent = document.getElementById('messageContent');
            messageContent.textContent = '';
            message.className = `alert alert-${type}`;
            message.classList.remove('d-none');
            
            if (Array.isArray(text)) {
                text.forEach((msg, idx) => {
                    const p = document.createElement('p');
                    p.className = idx > 0 ? 'mb-1' : 'mb-0';
                    p.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-circle' : 'check-circle'}"></i> ${msg}`;
                    messageContent.appendChild(p);
                });
            } else {
                messageContent.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-circle' : 'check-circle'}"></i> ${text}`;
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
</body>
</html>
