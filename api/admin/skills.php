<?php
// admin/skills.php
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    supabase_insert('skills', [
        'skill_name'     => htmlspecialchars($_POST['skill_name']),
        'skill_category' => htmlspecialchars($_POST['skill_category'])
    ]);
    header('Location: /admin/skills');
    exit;
}
if (isset($_GET['delete'])) {
    supabase_delete('skills', $_GET['delete']);
    header('Location: /admin/skills');
    exit;
}

$skills = supabase_fetch('skills');
$grouped = [];
foreach ($skills as $s) {
    $grouped[$s['skill_category']][] = $s;
}

// Color palette per category
$cat_palette = [
    'Programming & AI' => [
        'color'       => '#6366f1',  // Indigo
        'color_light' => 'rgba(99,102,241,0.1)',
        'icon'        => '🤖',
    ],
    'Database' => [
        'color'       => '#0ea5e9',  // Sky Blue
        'color_light' => 'rgba(14,165,233,0.1)',
        'icon'        => '🗄️',
    ],
    'Development' => [
        'color'       => '#10b981',  // Emerald Green
        'color_light' => 'rgba(16,185,129,0.1)',
        'icon'        => '💻',
    ],
    'Tools' => [
        'color'       => '#f59e0b',  // Amber
        'color_light' => 'rgba(245,158,11,0.1)',
        'icon'        => '🛠️',
    ],
    'UI/UX' => [
        'color'       => '#ec4899',  // Pink
        'color_light' => 'rgba(236,72,153,0.1)',
        'icon'        => '🎨',
    ],
    'Design Tools' => [
        'color'       => '#8b5cf6',  // Purple
        'color_light' => 'rgba(139,92,246,0.1)',
        'icon'        => '✏️',
    ],
];
$fallback = ['color' => '#64748b', 'color_light' => 'rgba(100,116,139,0.1)', 'icon' => '💡'];

include __DIR__ . '/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;">
        <strong style="color: var(--text-primary);"><?= count($skills) ?></strong> skills across
        <strong style="color: var(--text-primary);"><?= count($grouped) ?></strong> categories
    </p>
    <button class="btn-admin-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        + Add Skill
    </button>
</div>

<!-- Skill Category Grid -->
<div class="row g-4">
    <?php foreach ($grouped as $category => $items):
        $palette = $cat_palette[$category] ?? $fallback;
        $color       = $palette['color'];
        $color_light = $palette['color_light'];
        $icon        = $palette['icon'];
    ?>
    <div class="col-md-6 col-xl-4">
        <div class="skill-cat-card h-100"
             style="--cat-color: <?= $color ?>; --cat-color-light: <?= $color_light ?>;">

            <!-- Card Header -->
            <div class="skill-cat-header">
                <div class="skill-cat-icon"><?= $icon ?></div>
                <h3 class="skill-cat-name"><?= htmlspecialchars($category) ?></h3>
                <span class="skill-cat-count"><?= count($items) ?></span>
            </div>

            <!-- Skill Tags -->
            <div class="skill-cat-body">
                <?php foreach ($items as $skill): ?>
                <span class="skill-tag">
                    <?= htmlspecialchars($skill['skill_name']) ?>
                    <a href="?delete=<?= $skill['id'] ?>"
                       class="skill-tag-del"
                       title="Remove skill"
                       onclick="return confirm('Remove \'<?= htmlspecialchars($skill['skill_name']) ?>\'?');">✕</a>
                </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Add Skill Modal -->
<div class="modal fade" id="addModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="post" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">⚡ Add New Skill</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="action" value="add">
                <div class="form-group">
                    <label class="form-label-admin">Skill Name</label>
                    <input type="text" name="skill_name" class="form-control-admin"
                           placeholder="e.g. TensorFlow, Docker…" required autofocus>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label-admin">Category</label>
                    <select name="skill_category" class="form-control-admin" required>
                        <option value="" disabled selected>— Select a category —</option>
                        <?php foreach (array_keys($cat_palette) as $cat): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endforeach; ?>
                        <?php foreach (array_keys($grouped) as $cat):
                            if (!isset($cat_palette[$cat])): ?>
                        <option value="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></option>
                        <?php endif; endforeach; ?>
                        <option value="Other">Other</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn-admin-primary">Add Skill</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/footer.php'; ?>
