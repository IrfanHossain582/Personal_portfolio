<?php
// admin/dashboard.php
include 'header.php';

$skills = supabase_fetch('skills', 'id');
$projects = supabase_fetch('projects', 'id');
$messages = supabase_fetch('contact_messages', 'id,status');
$education = supabase_fetch('education', 'id');
$achievements = supabase_fetch('achievements', 'id');
$experience = supabase_fetch('experience', 'id');

$skills_count = count($skills);
$projects_count = count($projects);
$messages_count = count($messages);
$education_count = count($education);

// Handle Logo Upload
$logo_msg = '';
$logo_msg_type = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'upload_logo') {
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../../assets/img/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);

        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];

        if (in_array($ext, $allowed)) {
            $filename = 'logo_' . time() . '.' . $ext;
            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $filename)) {
                $profiles = supabase_fetch('profile');
                $profile_id = $profiles[0]['id'] ?? null;
                if ($profile_id) {
                    supabase_update('profile', $profile_id, ['logo_url' => 'assets/img/' . $filename]);
                    // Re-fetch profile after update
                    $profiles = supabase_fetch('profile');
                    $profile = !empty($profiles) ? $profiles[0] : [];
                    $logo_url = $profile['logo_url'] ?? '';
                    $logo_msg = '✅ Logo uploaded successfully!';
                    $logo_msg_type = 'success';
                }
            } else {
                $logo_msg = 'Failed to move uploaded file.';
                $logo_msg_type = 'danger';
            }
        } else {
            $logo_msg = 'Invalid file type. Allowed: JPG, PNG, SVG, WEBP.';
            $logo_msg_type = 'danger';
        }
    } else {
        $logo_msg = 'Please select a valid image file.';
        $logo_msg_type = 'danger';
    }
}

// Stats
$stats = [
    ['label' => 'Projects', 'value' => $projects_count, 'icon' => '🚀', 'color' => 'indigo'],
    ['label' => 'Skills', 'value' => $skills_count, 'icon' => '⚡', 'color' => 'purple'],
    ['label' => 'Messages', 'value' => $messages_count, 'icon' => '✉️', 'color' => 'cyan'],
    ['label' => 'Education', 'value' => $education_count, 'icon' => '🎓', 'color' => 'green'],
];
?>

<!-- ===== STATS ROW ===== -->
<div class="row g-4 mb-4">
    <?php foreach ($stats as $stat): ?>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon <?= $stat['color'] ?>"><?= $stat['icon'] ?></div>
            <div class="stat-number"><?= $stat['value'] ?></div>
            <div class="stat-label"><?= $stat['label'] ?></div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4">

    <!-- ===== LOGO UPLOAD ===== -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h2 class="admin-card-title">🖼️ Site Logo</h2>
            </div>
            <div class="admin-card-body">
                <?php if ($logo_msg): ?>
                <div class="alert-admin alert-<?= $logo_msg_type ?>"><?= htmlspecialchars($logo_msg) ?></div>
                <?php endif; ?>

                <!-- Current Logo Preview -->
                <div class="text-center mb-4">
                    <?php if (!empty($logo_url)): ?>
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 18px; overflow: hidden; background: #f8fafc; border: 2px solid #e2e8f0;">
                        <img src="../../<?= htmlspecialchars($logo_url) ?>" alt="Logo" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b;">Current logo</p>
                    <?php else: ?>
                    <div class="d-inline-flex align-items-center justify-content-center mb-3" style="width: 90px; height: 90px; border-radius: 18px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; font-size: 2rem; font-weight: 800;">
                        <?= $initials ?>
                    </div>
                    <p style="font-size: 0.78rem; color: #64748b;">No logo set yet</p>
                    <?php endif; ?>
                </div>

                <!-- Upload Form -->
                <form method="post" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="upload_logo">
                    <div class="upload-zone mb-3">
                        <input type="file" name="logo" accept="image/*">
                        <div class="upload-icon">📁</div>
                        <div class="upload-text">Click or drag logo here</div>
                        <div class="upload-hint">PNG, SVG, JPG or WEBP • Max 2MB</div>
                    </div>
                    <button type="submit" class="btn-admin-primary w-100 justify-content-center" style="justify-content: center;">
                        Upload Logo
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- ===== QUICK LINKS ===== -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h2 class="admin-card-title">⚡ Quick Actions</h2>
            </div>
            <div class="admin-card-body">
                <div class="d-flex flex-column gap-3">
                    <a href="profile.php" class="btn-admin-outline w-100" style="justify-content: flex-start;">
                        👤 &nbsp; Edit Profile & Bio
                    </a>
                    <a href="skills.php" class="btn-admin-outline w-100" style="justify-content: flex-start;">
                        ⚡ &nbsp; Manage Skills
                    </a>
                    <a href="projects.php" class="btn-admin-outline w-100" style="justify-content: flex-start;">
                        🚀 &nbsp; Add Project
                    </a>
                    <a href="messages.php" class="btn-admin-outline w-100" style="justify-content: flex-start;">
                        ✉️ &nbsp; View Messages
                    </a>
                    <a href="/" target="_blank" class="btn-admin-outline w-100" style="justify-content: flex-start;">
                        🌐 &nbsp; Open Portfolio Site
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RECENT MESSAGES ===== -->
    <div class="col-lg-4">
        <div class="admin-card h-100">
            <div class="admin-card-header">
                <h2 class="admin-card-title">✉️ Recent Messages</h2>
                <a href="messages.php" class="btn-admin-outline" style="padding: 6px 14px; font-size: 0.78rem;">View All</a>
            </div>
            <?php
            $recent_msgs = supabase_fetch('contact_messages');
            $recent_msgs = array_slice($recent_msgs, 0, 5);
            ?>
            <?php if (empty($recent_msgs)): ?>
            <div class="admin-card-body text-center" style="color: #94a3b8; padding: 40px;">
                <div style="font-size: 2rem; margin-bottom: 8px;">📭</div>
                No messages yet
            </div>
            <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>From</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent_msgs as $msg): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($msg['name']) ?></div>
                            <div style="font-size: 0.75rem; color: #94a3b8;"><?= htmlspecialchars($msg['email']) ?></div>
                        </td>
                        <td style="font-size: 0.78rem; color: #64748b; white-space: nowrap;">
                            <?= date('M d', strtotime($msg['created_at'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- ===== OVERVIEW ROW ===== -->
<div class="row g-4 mt-1">
    <div class="col-12">
        <div class="admin-card">
            <div class="admin-card-header">
                <h2 class="admin-card-title">📈 Portfolio Summary</h2>
            </div>
            <div class="admin-card-body">
                <div class="row g-4 text-center">
                    <?php
                    $summary = [
                        ['🚀', $projects_count, 'Projects'],
                        ['⚡', $skills_count, 'Skills'],
                        ['🎓', $education_count, 'Education'],
                        ['🏆', count($achievements), 'Achievements'],
                        ['💼', count($experience), 'Experience'],
                        ['✉️', $messages_count, 'Messages'],
                    ];
                    foreach ($summary as $s):
                    ?>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="summary-item">
                            <div class="summary-icon"><?= $s[0] ?></div>
                            <div class="summary-value"><?= $s[1] ?></div>
                            <div class="summary-label"><?= $s[2] ?></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
