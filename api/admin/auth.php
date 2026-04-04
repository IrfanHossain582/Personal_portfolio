<?php
// admin/auth.php
define('AUTH_SECRET_KEY', getenv('AUTH_SECRET_KEY') ?: 'portfolio_secure_key_2026_irfan');

function get_auth_token() {
    return hash_hmac('sha256', 'admin_logged_in', AUTH_SECRET_KEY);
}

function is_logged_in() {
    if (!isset($_COOKIE['admin_token'])) return false;
    return hash_equals(get_auth_token(), $_COOKIE['admin_token']);
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: /login');
        exit;
    }
}
?>
