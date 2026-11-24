<?php
require_once 'config/app.php';
requireRole('client');
$job_id = intval($_GET['job_id'] ?? 0);
$db = getDB();
$job = $db->query("SELECT * FROM job WHERE id=$job_id")->fetch_assoc();
$total = $job ? ($job['pay_rate_per_hour'] * $job['expected_duration_in_hours']) : 0;
$pageTitle = 'Payment';
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
    <a href="<?= BASE_URL ?>/index.php?page=my_jobs" class="btn btn-outline btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back</a>
    <div class="page-title">Job Payment</div>
    <div class="page-subtitle">Secure payment for completed work</div>
    <?php if($job): ?>
    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;max-width:800px;">
        <div class="card">
            <div class="card-header">Payment Details</div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/index.php">
                    <input type="hidden" name="action" value="submit_payment">
                    <input type="hidden" name="job_id" value="<?= $job_id ?>">
                    <input type="hidden" name="amount" value="<?= $total ?>">
                    <div class="form-group">
                        <label class="form-label">Phone Number (for payment)</label>
                        <input type="text" name="phone_number" class="form-control" required placeholder="03001234567">
                    </div>
                    <div class="alert alert-info"><i class="bi bi-info-circle" style="margin-right:6px;"></i>This is a simulated payment for demonstration purposes.</div>
                    <button type="submit" class="btn btn-success w-100" style="justify-content:center;padding:13px;"><i class="bi bi-credit-card"></i> Pay PKR <?= number_format($total,0) ?></button>
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">Summary</div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                <div>
                    <div style="font-size:12px;color:var(--muted);margin-bottom:4px;">Job Title</div>
                    <div style="font-weight:600;"><?= htmlspecialchars($job['title']) ?></div>
                </div>
                <hr style="border-color:var(--border);">
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted);font-size:13px;">Rate/hr</span><span>PKR <?= number_format($job['pay_rate_per_hour'],0) ?></span></div>
                <div style="display:flex;justify-content:space-between;"><span style="color:var(--muted);font-size:13px;">Duration</span><span><?= $job['expected_duration_in_hours'] ?> hrs</span></div>
                <hr style="border-color:var(--border);">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span style="font-weight:700;">Total</span>
                    <span style="font-family:'Syne',sans-serif;font-size:1.3rem;font-weight:800;color:var(--accent3);">PKR <?= number_format($total,0) ?></span>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="alert alert-danger">Job not found.</div>
    <?php endif; ?>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
