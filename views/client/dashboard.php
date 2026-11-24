<?php
require_once 'config/app.php';
requireRole('client');
$db = getDB();
$cl = $db->prepare("SELECT c.*, u.first_name, u.last_name FROM client c JOIN user u ON c.user_id=u.id WHERE c.user_id=?");
$cl->bind_param("i", $_SESSION['user_id']); $cl->execute();
$client = $cl->get_result()->fetch_assoc();
$client_id = $client['id'];
$total_jobs      = $db->query("SELECT COUNT(*) as c FROM job WHERE client_id=$client_id")->fetch_assoc()['c'];
$active_jobs     = $db->query("SELECT COUNT(*) as c FROM job WHERE client_id=$client_id AND is_active=1")->fetch_assoc()['c'];
$total_proposals = $db->query("SELECT COUNT(*) as c FROM job_proposal jp JOIN job j ON jp.job_id=j.id WHERE j.client_id=$client_id")->fetch_assoc()['c'];
$completed       = $db->query("SELECT COUNT(*) as c FROM job_proposal jp JOIN job j ON jp.job_id=j.id WHERE j.client_id=$client_id AND jp.status='completed successfully'")->fetch_assoc()['c'];
$recent_jobs     = $db->query("SELECT j.*, (SELECT COUNT(*) FROM job_proposal WHERE job_id=j.id) as proposals FROM job j WHERE j.client_id=$client_id ORDER BY j.time_created DESC LIMIT 5");
$pageTitle = 'Client Dashboard';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Client</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=post_job"><i class="bi bi-plus-circle"></i> Post a Job</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_jobs"><i class="bi bi-briefcase"></i> My Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title fade-up">Welcome, <?= htmlspecialchars($client['first_name']) ?>! 👋</div>
    <div class="page-subtitle fade-up">Here's your client overview</div>
    <div class="stat-grid fade-up-2">
        <div class="stat-card blue"><div class="stat-num"><?= $total_jobs ?></div><div class="stat-label">Total Jobs</div></div>
        <div class="stat-card green"><div class="stat-num"><?= $active_jobs ?></div><div class="stat-label">Active Jobs</div></div>
        <div class="stat-card gold"><div class="stat-num"><?= $total_proposals ?></div><div class="stat-label">Proposals</div></div>
        <div class="stat-card pink"><div class="stat-num"><?= $completed ?></div><div class="stat-label">Completed</div></div>
    </div>
    <div class="card fade-up-3">
        <div class="card-header">
            <span><i class="bi bi-briefcase" style="margin-right:8px;color:var(--accent)"></i>Recent Jobs</span>
            <a href="<?= BASE_URL ?>/index.php?page=post_job" class="btn btn-primary btn-sm"><i class="bi bi-plus"></i> Post Job</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Proposals</th><th>Deadline</th><th>Status</th><th></th></tr></thead>
                <tbody>
                <?php while($job = $recent_jobs->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($job['title']) ?></strong></td>
                    <td><span class="badge badge-blue"><?= $job['proposals'] ?></span></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d, Y', strtotime($job['receive_job_proposals_deadline'])) ?></td>
                    <td><?= $job['is_active'] ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Closed</span>' ?></td>
                    <td><a href="<?= BASE_URL ?>/index.php?page=job_proposals&job_id=<?= $job['id'] ?>" class="btn btn-outline btn-sm">View →</a></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
