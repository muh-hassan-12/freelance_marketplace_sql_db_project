<?php
require_once 'config/app.php';
requireAdmin();
$db = getDB();
$skills = $db->query("SELECT s.*, COUNT(fs.skill_id) as usage_count FROM skill s LEFT JOIN freelancer_skill fs ON fs.skill_id=s.id GROUP BY s.id ORDER BY s.name");
$pageTitle = 'Manage Skills';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_jobs"><i class="bi bi-briefcase"></i> Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_skills" class="active"><i class="bi bi-tags"></i> Skills</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_logs"><i class="bi bi-journal-text"></i> Logs</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Skills</div>
    <div class="page-subtitle">Manage available skills for freelancers</div>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="card" style="align-self:start;">
            <div class="card-header">Add New Skill</div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/index.php">
                    <input type="hidden" name="action" value="add_skill">
                    <div class="form-group">
                        <label class="form-label">Skill Name</label>
                        <input type="text" name="skill_name" class="form-control" required placeholder="e.g. Python">
                    </div>
                    <button class="btn btn-primary w-100" style="justify-content:center;">Add Skill</button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">All Skills</div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Skill</th><th>Used By</th><th></th></tr></thead>
                    <tbody>
                    <?php while($sk = $skills->fetch_assoc()): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($sk['name']) ?></strong></td>
                        <td><span class="badge badge-blue"><?= $sk['usage_count'] ?> freelancers</span></td>
                        <td>
                            <form method="POST" action="<?= BASE_URL ?>/index.php" style="display:inline;">
                                <input type="hidden" name="action" value="delete_skill">
                                <input type="hidden" name="skill_id" value="<?= $sk['id'] ?>">
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this skill?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
