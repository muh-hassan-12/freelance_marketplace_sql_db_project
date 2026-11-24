<?php
require_once 'config/app.php';
requireRole('client');
$db = getDB();
$cl = $db->prepare("SELECT id FROM client WHERE user_id=?");
$cl->bind_param("i",$_SESSION['user_id']); $cl->execute();
$client = $cl->get_result()->fetch_assoc();
$client_id = $client['id'];
$jobs = $db->query("SELECT j.*, (SELECT COUNT(*) FROM job_proposal WHERE job_id=j.id) as proposals FROM job j WHERE j.client_id=$client_id ORDER BY j.time_created DESC");
$pageTitle = 'My Jobs';
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
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
        <div class="page-title" style="margin-bottom:0;">My Jobs</div>
        <a href="<?= BASE_URL ?>/index.php?page=post_job" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Post New Job</a>
    </div>
    <div class="page-subtitle">Manage all your posted jobs</div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Rate/hr</th><th>Proposals</th><th>Deadline</th><th>Status</th><th>Actions</th></tr></thead>
                <tbody>
                <?php
                $all_jobs = [];
                while($job = $jobs->fetch_assoc()) $all_jobs[] = $job;
                if(empty($all_jobs)):
                ?>
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--muted);">No jobs posted yet. <a href="<?= BASE_URL ?>/index.php?page=post_job">Post your first job →</a></td></tr>
                <?php else: ?>
                <?php foreach($all_jobs as $job): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                    <td><span class="badge badge-blue">PKR <?= number_format($job['pay_rate_per_hour'],0) ?></span></td>
                    <td><span class="badge badge-cyan"><?= $job['proposals'] ?></span></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d, Y', strtotime($job['receive_job_proposals_deadline'])) ?></td>
                    <td><?= $job['is_active'] ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Closed</span>' ?></td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="<?= BASE_URL ?>/index.php?page=job_proposals&job_id=<?= $job['id'] ?>" class="btn btn-outline btn-sm">Proposals</a>
                            <a href="<?= BASE_URL ?>/index.php?page=payment&job_id=<?= $job['id'] ?>" class="btn btn-success btn-sm">Pay</a>
                            <form method="POST" action="<?= BASE_URL ?>/index.php" style="display:inline;">
                                <input type="hidden" name="action" value="toggle_job">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                <button class="btn btn-sm" style="background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);"><?= $job['is_active'] ? 'Close' : 'Open' ?></button>
                            </form>
                            <form method="POST" action="<?= BASE_URL ?>/index.php" style="display:inline;" onsubmit="return confirm('Delete this job?')">
                                <input type="hidden" name="action" value="delete_job">
                                <input type="hidden" name="job_id" value="<?= $job['id'] ?>">
                                <button class="btn btn-danger btn-sm"><i class="bi bi-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
