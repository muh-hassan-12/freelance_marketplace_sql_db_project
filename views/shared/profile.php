<?php
require_once 'config/app.php';
requireLogin();
$db = getDB();
$stmt = $db->prepare("SELECT * FROM user WHERE id=?");
$stmt->bind_param("i", $_SESSION['user_id']); $stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$profile = null; $skills_selected = [];
if (isClient()) {
    $p = $db->prepare("SELECT * FROM client WHERE user_id=?");
    $p->bind_param("i",$_SESSION['user_id']); $p->execute();
    $profile = $p->get_result()->fetch_assoc();
} elseif (isFreelancer()) {
    $p = $db->prepare("SELECT * FROM freelancer WHERE user_id=?");
    $p->bind_param("i",$_SESSION['user_id']); $p->execute();
    $profile = $p->get_result()->fetch_assoc();
    if ($profile) {
        $ss = $db->query("SELECT skill_id FROM freelancer_skill WHERE freelancer_id=".$profile['id']);
        while($r=$ss->fetch_assoc()) $skills_selected[] = $r['skill_id'];
    }
}
$all_skills = $db->query("SELECT * FROM skill ORDER BY name");
$pageTitle = 'My Profile';
require_once 'views/shared/header.php';

// Sidebar based on role
$role = $_SESSION['role'] ?? 'none';
?>
<?php if(isAdmin()): ?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="active"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<?php elseif($role === 'client'): ?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Client</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=post_job"><i class="bi bi-plus-circle"></i> Post a Job</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_jobs"><i class="bi bi-briefcase"></i> My Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="active"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<?php else: ?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Freelancer</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=browse_jobs"><i class="bi bi-search"></i> Browse Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_proposals"><i class="bi bi-file-text"></i> My Proposals</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile" class="active"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<?php endif; ?>

<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">My Profile</div>
    <div class="page-subtitle">Update your account information</div>
    <div class="card" style="max-width:680px;">
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/index.php">
                <input type="hidden" name="action" value="update_profile">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-control" value="<?= htmlspecialchars($user['first_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['last_name']) ?>" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Email <span style="color:var(--muted);font-size:12px;">(cannot change)</span></label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" disabled style="opacity:0.5;">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>
                    <div class="form-group">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($user['city'] ?? '') ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= htmlspecialchars($user['country'] ?? 'Pakistan') ?>">
                </div>
                <?php if ($profile): ?>
                <hr style="border-color:var(--border);margin:20px 0;">
                <div class="form-group">
                    <label class="form-label">Professional Title</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($profile['title']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Bio / Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars($profile['description']) ?></textarea>
                </div>
                <?php if (isFreelancer()): ?>
                <div class="form-group">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="years_of_experience" class="form-control" value="<?= $profile['years_of_experience'] ?>" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">My Skills</label>
                    <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:12px;max-height:160px;overflow-y:auto;display:grid;grid-template-columns:1fr 1fr 1fr;gap:4px;">
                        <?php $all_skills->data_seek(0); while($sk=$all_skills->fetch_assoc()): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $sk['id'] ?>" <?= in_array($sk['id'],$skills_selected)?'checked':'' ?>>
                            <label class="form-check-label"><?= htmlspecialchars($sk['name']) ?></label>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary" style="justify-content:center;padding:11px 28px;"><i class="bi bi-save"></i> Save Changes</button>
            </form>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
