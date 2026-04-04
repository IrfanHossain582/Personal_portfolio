<?php
// admin/auth.php
ini_set('session.save_path', '/tmp');
session_start();

function require_login() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: /login');
        exit;
    }
}
?>
