<?php
// admin/messages.php
include 'header.php';

if (isset($_GET['delete'])) {
    supabase_delete('contact_messages', $_GET['delete']);
    header('Location: messages.php'); exit;
}

$messages = supabase_fetch('contact_messages');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <p style="color: var(--text-muted); font-size: 0.85rem; margin: 0;"><?= count($messages) ?> messages total</p>
</div>

<div class="admin-card">
    <?php if (empty($messages)): ?>
    <div class="admin-card-body text-center" style="padding: 60px; color: #94a3b8;">
        <div style="font-size: 3rem; margin-bottom: 12px;">📭</div>
        <div style="font-weight: 600; font-size: 1rem; color: #64748b;">No messages yet</div>
        <div style="font-size: 0.85rem; margin-top: 4px;">When visitors submit your contact form, they'll appear here.</div>
    </div>
    <?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>From</th>
                <th>Message</th>
                <th>Date</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($messages as $msg): ?>
            <tr>
                <td>
                    <div style="font-weight: 600; color: #0f172a;"><?= htmlspecialchars($msg['name']) ?></div>
                    <a href="mailto:<?= htmlspecialchars($msg['email']) ?>" style="font-size: 0.78rem; color: #6366f1;"><?= htmlspecialchars($msg['email']) ?></a>
                </td>
                <td style="max-width: 340px; color: #475569; font-size: 0.875rem;">
                    <?= htmlspecialchars(substr($msg['message'], 0, 100)) ?>...
                </td>
                <td style="white-space: nowrap; font-size: 0.78rem; color: #94a3b8;">
                    <?= date('M d, Y', strtotime($msg['created_at'])) ?>
                </td>
                <td style="text-align: right;">
                    <a href="?delete=<?= $msg['id'] ?>" class="btn-admin-danger" onclick="return confirm('Delete this message?');">✕ Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
