<?php
// admin/achievements.php
include __DIR__ . '/header.php';

// Add new achievement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $data = [
        'title' => htmlspecialchars($_POST['title']),
        'description' => htmlspecialchars($_POST['description'])
    ];
    supabase_insert('achievements', $data);
    header("Location: achievements.php");
    exit;
}

// Delete achievement
if (isset($_GET['delete'])) {
    supabase_delete('achievements', $_GET['delete']);
    header("Location: achievements.php");
    exit;
}

$achievements = supabase_fetch('achievements');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Achievements</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Achievement</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($achievements as $ach): ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($ach['title']) ?></td>
                    <td><?= htmlspecialchars($ach['description']) ?></td>
                    <td>
                        <a href="?delete=<?= $ach['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?');">Delete</a>
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
                <h5 class="modal-title">Add Achievement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label>Achievement Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Achievement</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
