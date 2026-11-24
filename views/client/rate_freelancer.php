<?php
require_once 'config/app.php';
requireRole('client');
$job_id = intval($_GET['job_id'] ?? 0);
$pageTitle = 'Rate Freelancer';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Client</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=post_job"><i class="bi bi-plus-circle"></i> Post a Job</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_jobs" class="active"><i class="bi bi-briefcase"></i> My Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Rate the Freelancer</div>
    <div class="page-subtitle">Share your experience working with this freelancer</div>
    <div class="card" style="max-width:500px;">
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/index.php">
                <input type="hidden" name="action" value="submit_rating">
                <input type="hidden" name="job_id" value="<?= $job_id ?>">
                <input type="hidden" name="type" value="freelancer">
                <div class="form-group">
                    <label class="form-label">Rating</label>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <?php for($i=1;$i<=5;$i++): ?>
                        <label style="display:flex;align-items:center;gap:6px;cursor:pointer;padding:8px 14px;background:var(--surface);border:1px solid var(--border);border-radius:10px;font-size:14px;">
                            <input type="radio" name="rating" value="<?= $i ?>" required style="accent-color:var(--accent);">
                            <?= $i ?> ⭐
                        </label>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Comment</label>
                    <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience with this freelancer..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100" style="justify-content:center;"><i class="bi bi-star"></i> Submit Rating</button>
            </form>
        </div>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
