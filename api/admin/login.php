<?php
// admin/login.php
ini_set('session.save_path', '/tmp');
session_start();

if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'Irfan' && $password === 'Na@sif011202015') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — Portfolio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="login-wrapper">
    <div class="login-card">
        <!-- Logo -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center mb-4"
                 style="width: 56px; height: 56px; border-radius: 16px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; font-size: 1.4rem; font-weight: 800; box-shadow: 0 8px 24px rgba(99,102,241,0.3);">
                I
            </div>
            <h1 style="font-size: 1.5rem; font-weight: 800; color: #0f172a; letter-spacing: -0.5px; margin-bottom: 6px;">Admin Login</h1>
            <p style="color: #64748b; font-size: 0.875rem; margin: 0;">Sign in to access your portfolio dashboard</p>
        </div>

        <?php if ($error): ?>
        <div class="alert-admin alert-danger" style="margin-bottom: 20px;">
            ❌ <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label class="form-label-admin">Username</label>
                <input type="text" name="username" class="form-control-admin" placeholder="Enter your username" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label-admin">Password</label>
                <input type="password" name="password" class="form-control-admin" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn-admin-primary w-100 justify-content-center mt-2" style="padding: 13px 20px; font-size: 0.95rem; border-radius: 12px; justify-content: center;">
                Sign In →
            </button>
        </form>

        <p style="text-align: center; font-size: 0.78rem; color: #94a3b8; margin-top: 32px;">
            Irfan Hossain — AI & Automation Engineer
        </p>
    </div>
</div>
</body>
</html>
