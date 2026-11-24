<?php
require_once 'config/app.php';
requireAdmin();
$db = getDB();
$jobs = $db->query("SELECT j.*, u.first_name, u.last_name, (SELECT COUNT(*) FROM job_proposal WHERE job_id=j.id) as proposals FROM job j JOIN client c ON j.client_id=c.id JOIN user u ON c.user_id=u.id ORDER BY j.time_created DESC");
$pageTitle = 'Manage Jobs';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_jobs" class="active"><i class="bi bi-briefcase"></i> Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_skills"><i class="bi bi-tags"></i> Skills</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_logs"><i class="bi bi-journal-text"></i> Logs</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Jobs</div>
    <div class="page-subtitle">All jobs posted on the platform</div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Title</th><th>Client</th><th>Rate</th><th>Proposals</th><th>Deadline</th><th>Status</th></tr></thead>
                <tbody>
                <?php while($j = $jobs->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($j['title']) ?></strong></td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($j['first_name'].' '.$j['last_name']) ?></td>
                    <td><span class="badge badge-blue">PKR <?= number_format($j['pay_rate_per_hour'],0) ?>/hr</span></td>
                    <td><?= $j['proposals'] ?></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d, Y', strtotime($j['receive_job_proposals_deadline'])) ?></td>
                    <td><?= $j['is_active'] ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-gray">Closed</span>' ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
