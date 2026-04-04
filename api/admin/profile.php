<?php
// admin/profile.php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../../config/database.php';

$profiles = supabase_fetch('profile');
$profile = !empty($profiles) ? $profiles[0] : null;

$msg = '';
$msg_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $profile_id = $profile['id'] ?? null;
    $data = [
        'name' => htmlspecialchars($_POST['name']),
        'title' => htmlspecialchars($_POST['title']),
        'email' => htmlspecialchars($_POST['email']),
        'bio' => htmlspecialchars($_POST['bio']),
        'github_url' => htmlspecialchars($_POST['github_url']),
        'linkedin_url' => htmlspecialchars($_POST['linkedin_url']),
        'portfolio_url' => htmlspecialchars($_POST['portfolio_url'])
    ];

    // Profile Image Upload to Supabase Storage
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

        if (in_array($ext, $allowed)) {
            $filename   = 'profile_' . time() . '.' . $ext;
            $file_bytes = file_get_contents($_FILES['profile_image']['tmp_name']);
            $mime       = mime_content_type($_FILES['profile_image']['tmp_name']);
            $supa_url   = rtrim(getenv('SUPABASE_URL'), '/');
            if (!$supa_url) $supa_url = 'https://agrjfzzbykziwjdsonew.supabase.co';
            $supa_key   = getenv('SUPABASE_SERVICE_KEY');
            $bucket     = 'avatars';
            $ch = curl_init("$supa_url/storage/v1/object/$bucket/$filename");
            curl_setopt_array($ch, [
                CURLOPT_CUSTOMREQUEST  => 'POST',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER     => [
                    "Authorization: Bearer $supa_key",
                    "Content-Type: $mime",
                    "x-upsert: true",
                ],
                CURLOPT_POSTFIELDS => $file_bytes,
            ]);
            $res  = curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($code === 200 || $code === 201) {
                $public_url = "$supa_url/storage/v1/object/public/$bucket/$filename";
                $data['profile_image'] = $public_url;
            }
        }
    }

    if ($profile_id) {
        supabase_update('profile', $profile_id, $data);
    } else {
        supabase_insert('profile', $data);
    }

    header('Location: /admin/profile?success=1');
    exit;
}

if (isset($_GET['success'])) {
    $msg = 'Profile updated successfully!';
    $msg_type = 'success';
}

include __DIR__ . '/header.php';
?>

<?php if ($msg): ?>
<div class="alert-admin alert-<?= $msg_type ?> mb-4">✅ <?= htmlspecialchars($msg) ?></div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="update">
    <div class="row g-4">

        <!-- Profile Picture -->
        <div class="col-lg-4">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">🖼️ Profile Picture</h2>
                </div>
                <div class="admin-card-body text-center">
                    <?php if (!empty($profile['profile_image'])): ?>
                    <img src="<?= htmlspecialchars($profile['profile_image']) ?>" alt="Profile"
                         style="width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid #e2e8f0; margin-bottom: 16px;">
                    <?php else: ?>
                    <div style="width: 110px; height: 110px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: white; font-size: 2.5rem; font-weight: 800; display: flex; align-items: center; justify-content: center; margin: 0 auto 16px;">
                        <?= strtoupper(substr($profile['name'] ?? 'A', 0, 1)) ?>
                    </div>
                    <?php endif; ?>

                    <div class="upload-zone">
                        <input type="file" name="profile_image" accept="image/*">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">Click to change photo</div>
                        <div class="upload-hint">JPG, PNG or WEBP · Max 5MB</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Info -->
        <div class="col-lg-8">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">📋 Basic Information</h2>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">Full Name</label>
                                <input type="text" name="name" class="form-control-admin" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">Professional Title</label>
                                <input type="text" name="title" class="form-control-admin" value="<?= htmlspecialchars($profile['title'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">Email Address</label>
                                <input type="email" name="email" class="form-control-admin" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">Bio / About</label>
                                <textarea name="bio" class="form-control-admin" rows="4" required><?= htmlspecialchars($profile['bio'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Links -->
        <div class="col-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h2 class="admin-card-title">🔗 Social Links</h2>
                </div>
                <div class="admin-card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">GitHub URL</label>
                                <input type="url" name="github_url" class="form-control-admin" value="<?= htmlspecialchars($profile['github_url'] ?? '') ?>" placeholder="https://github.com/...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">LinkedIn URL</label>
                                <input type="url" name="linkedin_url" class="form-control-admin" value="<?= htmlspecialchars($profile['linkedin_url'] ?? '') ?>" placeholder="https://linkedin.com/in/...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label class="form-label-admin">Behance / Portfolio URL</label>
                                <input type="url" name="portfolio_url" class="form-control-admin" value="<?= htmlspecialchars($profile['portfolio_url'] ?? '') ?>" placeholder="https://behance.net/...">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button -->
        <div class="col-12 d-flex justify-content-end">
            <button type="submit" class="btn-admin-primary" style="padding: 13px 32px; font-size: 0.95rem;">
                💾 &nbsp; Save Changes
            </button>
        </div>
    </div>
</form>

<?php include __DIR__ . '/footer.php'; ?>
