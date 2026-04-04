<?php
// admin/experience.php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../../config/database.php';

// Add new experience
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $data = [
        'company_name' => htmlspecialchars($_POST['company_name']),
        'description' => htmlspecialchars($_POST['description'])
    ];
    supabase_insert('experience', $data);
    header("Location: /admin/experience");
    exit;
}

// Delete experience
if (isset($_GET['delete'])) {
    supabase_delete('experience', $_GET['delete']);
    header("Location: /admin/experience");
    exit;
}

$experience = supabase_fetch('experience');

include __DIR__ . '/header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Experience</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Experience</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Company / Role</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($experience as $exp): ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($exp['company_name']) ?></td>
                    <td><?= htmlspecialchars($exp['description']) ?></td>
                    <td>
                        <a href="/admin/experience?delete=<?= $exp['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?');">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Experience</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label>Company / Role Name</label>
                    <input type="text" name="company_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Experience</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
