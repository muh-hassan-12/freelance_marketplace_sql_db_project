<?php
require_once 'config/app.php';
requireRole('freelancer');
$proposal_id = intval($_GET['proposal_id'] ?? 0);
$pageTitle = 'Submit Work';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Freelancer</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=browse_jobs"><i class="bi bi-search"></i> Browse Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_proposals" class="active"><i class="bi bi-file-text"></i> My Proposals</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Submit Your Work</div>
    <div class="page-subtitle">Describe what you have delivered</div>
    <div class="card" style="max-width:600px;">
        <div class="card-body">
            <form method="POST" action="<?= BASE_URL ?>/index.php">
                <input type="hidden" name="action" value="submit_work">
                <input type="hidden" name="proposal_id" value="<?= $proposal_id ?>">
                <div class="form-group">
                    <label class="form-label">Work Description / Delivery Notes</label>
                    <textarea name="submission_description" class="form-control" rows="8" required placeholder="Describe what you have delivered, include links, notes, or any relevant info for the client..."></textarea>
                </div>
                <div style="display:flex;gap:10px;">
                    <a href="<?= BASE_URL ?>/index.php?page=my_proposals" class="btn btn-outline" style="flex:1;justify-content:center;">Cancel</a>
                    <button type="submit" class="btn btn-success" style="flex:1;justify-content:center;"><i class="bi bi-upload"></i> Submit Work</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
