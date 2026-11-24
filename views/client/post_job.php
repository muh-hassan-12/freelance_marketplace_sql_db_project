<?php
require_once 'config/app.php';
requireRole('client');
$db = getDB();
$skills = $db->query("SELECT * FROM skill ORDER BY name");
$pageTitle = 'Post a Job';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Client</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=post_job" class="active"><i class="bi bi-plus-circle"></i> Post a Job</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_jobs"><i class="bi bi-briefcase"></i> My Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Post a New Job</div>
    <div class="page-subtitle">Fill in the details to attract the best freelancers</div>
    <div class="card" style="max-width:680px;">
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/index.php">
                <input type="hidden" name="action" value="post_job">
                <div class="form-group">
                    <label class="form-label">Job Title</label>
                    <input type="text" name="title" class="form-control" required placeholder="e.g. React Developer Needed">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="5" required placeholder="Describe the project, requirements, deliverables…"></textarea>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Pay Rate (PKR/hr)</label>
                        <input type="number" name="pay_rate_per_hour" class="form-control" required min="0" step="0.01" placeholder="500">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Expected Duration (hours)</label>
                        <input type="number" name="expected_duration_in_hours" class="form-control" required min="0" step="0.5" placeholder="10">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Proposal Deadline</label>
                    <input type="datetime-local" name="deadline" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Required Skills</label>
                    <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:12px;max-height:160px;overflow-y:auto;display:grid;grid-template-columns:1fr 1fr 1fr;gap:4px;">
                        <?php while($sk = $skills->fetch_assoc()): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $sk['id'] ?>" id="sk<?= $sk['id'] ?>">
                            <label class="form-check-label" for="sk<?= $sk['id'] ?>"><?= htmlspecialchars($sk['name']) ?></label>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <button class="btn btn-primary" style="justify-content:center;">Post Job →</button>
            </form>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
