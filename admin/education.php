<?php
// admin/education.php
include 'header.php';

// Add new education
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $data = [
        'degree' => htmlspecialchars($_POST['degree']),
        'institution_name' => htmlspecialchars($_POST['institution_name']),
        'end_year' => (int)$_POST['end_year'],
        'cgpa' => htmlspecialchars($_POST['cgpa'])
    ];
    supabase_insert('education', $data);
    header("Location: education.php");
    exit;
}

// Delete education
if (isset($_GET['delete'])) {
    supabase_delete('education', $_GET['delete']);
    header("Location: education.php");
    exit;
}

$education = supabase_fetch('education');
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Manage Education</h2>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">Add Education</button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Degree</th>
                    <th>Institution</th>
                    <th>End Year</th>
                    <th>CGPA</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($education as $edu): ?>
                <tr>
                    <td class="fw-bold"><?= htmlspecialchars($edu['degree']) ?></td>
                    <td><?= htmlspecialchars($edu['institution_name']) ?></td>
                    <td><?= htmlspecialchars($edu['end_year']) ?></td>
                    <td><?= htmlspecialchars($edu['cgpa']) ?></td>
                    <td>
                        <a href="?delete=<?= $edu['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this record?');">Delete</a>
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
                <h5 class="modal-title">Add Education</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="mb-3">
                    <label>Degree</label>
                    <input type="text" name="degree" class="form-control" placeholder="e.g. BSc (CSE)" required>
                </div>
                <div class="mb-3">
                    <label>Institution Name</label>
                    <input type="text" name="institution_name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Passing Year</label>
                    <input type="number" name="end_year" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>CGPA</label>
                    <input type="text" name="cgpa" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Education</button>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
