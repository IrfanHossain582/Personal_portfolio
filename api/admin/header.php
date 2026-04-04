<?php
// admin/header.php
require_once 'auth.php';
require_login();
require_once '../../config/database.php';

// Get current page for active state
$current_page = basename($_SERVER['PHP_SELF']);

// Get profile for sidebar branding and logo
$profiles = supabase_fetch('profile');
$profile = !empty($profiles) ? $profiles[0] : [];
$name = $profile['name'] ?? 'Admin';
$first_name = explode(' ', $name)[0];
$initials = strtoupper(substr($name, 0, 1));
$logo_url = $profile['logo_url'] ?? '';

$nav_items = [
    ['file' => 'dashboard.php', 'icon' => '📊', 'label' => 'Dashboard'],
    ['file' => 'profile.php',   'icon' => '👤', 'label' => 'Profile'],
    ['file' => 'skills.php',    'icon' => '⚡', 'label' => 'Skills'],
    ['file' => 'projects.php',  'icon' => '🚀', 'label' => 'Projects'],
    ['file' => 'education.php', 'icon' => '🎓', 'label' => 'Education'],
    ['file' => 'experience.php','icon' => '💼', 'label' => 'Experience'],
    ['file' => 'achievements.php','icon' => '🏆', 'label' => 'Achievements'],
    ['file' => 'messages.php',  'icon' => '✉️', 'label' => 'Messages'],
];

// Page title map
$page_titles = [
    'dashboard.php'    => 'Dashboard',
    'profile.php'      => 'Manage Profile',
    'skills.php'       => 'Manage Skills',
    'projects.php'     => 'Manage Projects',
    'education.php'    => 'Manage Education',
    'experience.php'   => 'Manage Experience',
    'achievements.php' => 'Achievements',
    'messages.php'     => 'Contact Messages',
];
$page_title = $page_titles[$current_page] ?? 'Admin Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<div class="admin-wrapper">

    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">

        <!-- Brand -->
        <a href="dashboard.php" class="sidebar-brand">
            <div class="brand-logo-wrapper">
                <?php if (!empty($logo_url)): ?>
                    <img src="../../<?= htmlspecialchars($logo_url) ?>" alt="Logo">
                <?php else: ?>
                    <?= $initials ?>
                <?php endif; ?>
            </div>
            <div class="brand-text">
                <span class="brand-name"><?= htmlspecialchars($first_name) ?></span>
                <span class="brand-role">Portfolio Admin</span>
            </div>
        </a>

        <!-- Navigation -->
        <nav class="sidebar-nav">
            <div class="nav-section-label">Main Navigation</div>
            <?php foreach ($nav_items as $item): ?>
            <a href="<?= $item['file'] ?>" class="sidebar-link <?= $current_page === $item['file'] ? 'active' : '' ?>">
                <span class="nav-icon"><?= $item['icon'] ?></span>
                <?= htmlspecialchars($item['label']) ?>
            </a>
            <?php endforeach; ?>
        </nav>

        <!-- Sidebar Footer -->
        <div class="sidebar-footer">
            <a href="/" target="_blank" class="sidebar-link" style="color: #94a3b8;">
                <span class="nav-icon">🌐</span>
                View Site
            </a>
            <a href="logout.php" class="sidebar-link" style="color: #f87171;">
                <span class="nav-icon">🚪</span>
                Logout
            </a>
        </div>
    </aside>

    <!-- ===== MAIN CONTENT ===== -->
    <main class="main-content">
        <!-- Top Header -->
        <header class="content-header">
            <div>
                <h1 class="page-title"><?= htmlspecialchars($page_title) ?></h1>
            </div>
            <div class="header-actions">
                <span class="header-badge">Admin Panel</span>
                <div class="admin-avatar" title="<?= htmlspecialchars($name) ?>">
                    <?php if (!empty($profile['profile_image'])): ?>
                        <img src="../../<?= htmlspecialchars($profile['profile_image']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                    <?php else: ?>
                        <?= $initials ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <!-- Page Body -->
        <div class="content-body">
