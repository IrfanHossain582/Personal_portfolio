<?php
// admin/projects.php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    supabase_insert('projects', [
        'project_name'    => htmlspecialchars($_POST['project_name']),
        'technology_used' => htmlspecialchars($_POST['technology_used']),
        'description'     => htmlspecialchars($_POST['description']),
        'live_demo_link'  => htmlspecialchars($_POST['live_demo_link'])
    ]);
    header('Location: /admin/projects');
    exit;
}
if (isset($_GET['delete'])) {
    supabase_delete('projects', $_GET['delete']);
    header('Location: /admin/projects');
    exit;
}

$projects = supabase_fetch('projects');

include __DIR__ . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;"><?= count($projects) ?> projects total</p>
    <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        + Add Project
    </button>
</div>

<div class="admin-card">
    <table class="admin-table">
        <thead>
            <tr>
                <th>Project Name</th>
                <th>Technologies</th>
                <th>Live Demo</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($projects)): ?>
            <tr><td colspan="4" style="text-align:center; padding: 48px; color: #94a3b8;">No projects yet. Add your first one!</td></tr>
            <?php else: foreach ($projects as $p): ?>
            <tr>
                <td>
                    <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($p['project_name']) ?></div>
                    <div style="font-size: 0.78rem; color: #94a3b8; margin-top: 2px;"><?= htmlspecialchars(substr($p['description'] ?? '', 0, 60)) ?>...</div>
                </td>
                <td><span class="badge-admin badge-indigo"><?= htmlspecialchars($p['technology_used'] ?? '—') ?></span></td>
                <td>
                    <?php if (!empty($p['live_demo_link'])): ?>
                    <a href="<?= htmlspecialchars($p['live_demo_link']) ?>" target="_blank" style="color: #6366f1; font-size: 0.82rem; font-weight: 500;">View ↗</a>
                    <?php else: ?>
                    <span style="color: #cbd5e1; font-size: 0.82rem;">—</span>
                    <?php endif; ?>
                </td>
                <td style="text-align: right;">
                    <a href="?delete=<?= $p['id'] ?>" class="btn-admin-danger" onclick="return confirm('Delete this project?');">✕ Delete</a>
                </td>
            </tr>
            <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label class="form-label-admin">Project Name</label>
                    <input type="text" name="project_name" class="form-control-admin" placeholder="e.g. DDoS Classification" required>
                </div>
                <div class="form-group">
                    <label class="form-label-admin">Technologies</label>
                    <input type="text" name="technology_used" class="form-control-admin" placeholder="e.g. Machine Learning, Python" required>
                </div>
                <div class="form-group">
                    <label class="form-label-admin">Live Demo URL</label>
                    <input type="url" name="live_demo_link" class="form-control-admin" placeholder="https://...">
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label-admin">Description</label>
                    <textarea name="description" class="form-control-admin" placeholder="Short project description..." required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-admin-primary">Add Project</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
