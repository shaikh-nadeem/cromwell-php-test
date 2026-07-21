<?php
session_start();
require_once __DIR__ . '/../config.php';
$baseUrl = rtrim($config['app']['base_url'], '/');
if (empty($_SESSION['logged_in'])) {
    header('Location: ' . $baseUrl . '/user/login');
    exit;
}
$currentEmail = (string) ($_SESSION['user']['email'] ?? '');
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile | PHP Test</title>
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
        .btn-success {
            background: linear-gradient(to right, #198754, #20c997);
            border: none;
        }
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(25, 135, 84, 0.3);
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
        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
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
        @media (max-width: 768px) {
            .header-actions {
                width: 100%;
                margin-top: 1rem;
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
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Card Header -->
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <div>
                                <h1 class="h2 mb-2 fw-bold">
                                    <i class="bi bi-pencil-square text-primary"></i> Edit Your Profile
                                </h1>
                                <p class="text-muted mb-0">Update your personal information and account settings</p>
                            </div>
                            <div class="header-actions">
                                <a class="btn btn-outline-secondary" href="<?= htmlspecialchars($baseUrl) ?>/user/home">
                                    <i class="bi bi-house-door"></i> Dashboard
                                </a>
                                <a class="btn btn-outline-danger" href="<?= htmlspecialchars($baseUrl) ?>/user/logout">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Alert -->
                <div id="message" class="alert d-none" role="alert">
                    <div id="messageContent"></div>
                </div>

                <!-- Lookup Section -->
                <div class="card mb-4">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-search text-primary"></i> Step 1: Search Your Profile
                        </h5>
                        <form id="lookup-form" class="row g-3">
                            <div class="col-lg-9">
                                <label for="lookup-email" class="form-label">Email Address</label>
                                <input id="lookup-email" name="email" type="email" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($currentEmail) ?>" required 
                                       placeholder="Enter your email address">
                            </div>
                            <div class="col-lg-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100" id="loadBtn">
                                    <i class="bi bi-search"></i> Load Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Form Section -->
                <div class="card" id="edit-form-card" style="display:none;">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-4">
                            <i class="bi bi-file-earmark-text text-primary"></i> Step 2: Edit Your Information
                        </h5>
                        
                        <form id="edit-form" class="row g-4">
                            <input type="hidden" name="email">

                            <!-- Personal Information Section -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-person"></i> Personal Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <label for="title" class="form-label">Title</label>
                                        <select id="title" name="title" class="form-select">
                                            <option value="">Select title</option>
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
                                               placeholder="e.g., John">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="surname" class="form-label">Last Name</label>
                                        <input id="surname" name="surname" type="text" class="form-control" 
                                               placeholder="e.g., Smith">
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information Section -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-telephone"></i> Contact Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="phone_mobile" class="form-label">Mobile Phone</label>
                                        <input id="phone_mobile" name="phone_mobile" type="tel" class="form-control" 
                                               placeholder="e.g., +1 (555) 123-4567">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="phone_other" class="form-label">Other Phone</label>
                                        <input id="phone_other" name="phone_other" type="tel" class="form-control" 
                                               placeholder="e.g., +1 (555) 987-6543">
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Information Section -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-calendar"></i> Additional Information
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="form-label">Date of Birth</label>
                                        <input id="date_of_birth" name="date_of_birth" type="date" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <!-- Security Section -->
                            <div class="col-12">
                                <div class="form-section-title">
                                    <i class="bi bi-lock"></i> Security
                                </div>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label for="password" class="form-label">New Password</label>
                                        <div class="password-toggle-group">
                                            <input id="password" name="password" type="password" class="form-control password-field-with-toggle" 
                                                   placeholder="Leave blank to keep current password">
                                            <button type="button" class="password-toggle-icon" data-target="password" title="Toggle password visibility">
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength" id="passwordStrength"></div>
                                        <small class="form-text text-muted d-block mt-2">
                                            <i class="bi bi-info-circle"></i> Only fill this if you want to change your password
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12 pt-3 border-top">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-success btn-lg" id="saveBtn">
                                        <i class="bi bi-check-circle"></i> Save Changes
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary btn-lg">
                                        <i class="bi bi-arrow-clockwise"></i> Reset Form
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const baseUrl = <?= json_encode($baseUrl, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const lookupForm = document.getElementById('lookup-form');
        const editForm = document.getElementById('edit-form');
        const editFormCard = document.getElementById('edit-form-card');
        const messageDiv = document.getElementById('message');
        const messageContent = document.getElementById('messageContent');
        const loadBtn = document.getElementById('loadBtn');
        const saveBtn = document.getElementById('saveBtn');
        const passwordField = document.getElementById('password');
        const strengthDiv = document.getElementById('passwordStrength');
        const passwordRegex = /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&^#()_+=\-\[\]{};:'",.<>?\/\\|`]).{8,}$/;

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

        function showMessage(text, type = 'info') {
            messageContent.textContent = '';
            messageDiv.className = `alert alert-${type}`;
            messageDiv.classList.remove('d-none');
            
            if (Array.isArray(text)) {
                text.forEach((msg, idx) => {
                    const p = document.createElement('p');
                    p.className = idx > 0 ? 'mb-1' : 'mb-0';
                    p.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i> ${msg}`;
                    messageContent.appendChild(p);
                });
            } else {
                messageContent.innerHTML = `<i class="bi bi-${type === 'danger' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'}"></i> ${text}`;
            }
            
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function setLoadingState(btn, isLoading) {
            if (isLoading) {
                btn.disabled = true;
                btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...`;
            } else {
                btn.disabled = false;
                btn.innerHTML = btn === loadBtn ? '<i class="bi bi-search"></i> Load Profile' : '<i class="bi bi-check-circle"></i> Save Changes';
            }
        }

        function validatePasswordStrength(password) {
            const checks = {
                'length': password.length >= 8,
                'uppercase': /[A-Z]/.test(password),
                'lowercase': /[a-z]/.test(password),
                'number': /\d/.test(password),
                'special': /[@$!%*?&^#()_+=\-\[\]{};:'",.<>?\/\\|`]/.test(password),
            };

            const allValid = Object.values(checks).every(v => v);
            
            if (password.length > 0) {
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
                passwordField.classList.toggle('is-valid', allValid);
                passwordField.classList.toggle('is-invalid', !allValid);
            } else {
                strengthDiv.innerHTML = '';
                passwordField.classList.remove('is-valid', 'is-invalid');
            }

            return allValid;
        }

        // Password validation on change
        passwordField.addEventListener('input', function() {
            validatePasswordStrength(this.value);
        });

        // Load Profile Handler
        lookupForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            setLoadingState(loadBtn, true);

            try {
                const email = document.getElementById('lookup-email').value;
                const response = await fetch(baseUrl + '/api/user?email=' + encodeURIComponent(email));
                const data = await response.json();

                if (!data.success) {
                    showMessage(data.errors || ['Profile lookup failed.'], 'danger');
                    editFormCard.style.display = 'none';
                    return;
                }

                showMessage('✓ Profile loaded successfully', 'success');
                editFormCard.style.display = 'block';
                
                // Populate form fields
                Object.entries(data.user).forEach(([key, value]) => {
                    const field = editForm.elements[key];
                    if (field) {
                        field.value = value || '';
                    }
                });
                editForm.elements.email.value = data.user.email;
                
                // Smooth scroll to form
                editFormCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            } catch (error) {
                showMessage('Connection error: ' + error.message, 'danger');
                editFormCard.style.display = 'none';
            } finally {
                setLoadingState(loadBtn, false);
            }
        });

        // Save Profile Handler
        editForm.addEventListener('submit', async function (event) {
            event.preventDefault();
            setLoadingState(saveBtn, true);

            try {
                const formData = new FormData(editForm);
                const payload = Object.fromEntries(formData.entries());
                
                // Validate password if provided
                if (payload.password !== '') {
                    if (!validatePasswordStrength(payload.password)) {
                        showMessage('Password does not meet all requirements.', 'danger');
                        setLoadingState(saveBtn, false);
                        return;
                    }
                } else {
                    delete payload.password;
                }

                const response = await fetch(baseUrl + '/api/user', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });

                const data = await response.json();
                
                if (data.success) {
                    showMessage('✓ Profile updated successfully!', 'success');
                    // Clear password field after successful save
                    editForm.elements.password.value = '';
                    strengthDiv.innerHTML = '';
                    passwordField.classList.remove('is-valid', 'is-invalid');
                } else {
                    showMessage(data.errors || ['Profile update failed.'], 'danger');
                }
            } catch (error) {
                showMessage('Connection error: ' + error.message, 'danger');
            } finally {
                setLoadingState(saveBtn, false);
            }
        });
    </script>
</body>
</html>
