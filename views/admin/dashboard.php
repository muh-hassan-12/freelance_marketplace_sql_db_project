<?php
require_once 'config/app.php';
requireAdmin();
$db = getDB();
$page = 'admin';
$total_users     = $db->query("SELECT COUNT(*) as c FROM user WHERE is_admin=0")->fetch_assoc()['c'];
$total_jobs      = $db->query("SELECT COUNT(*) as c FROM job")->fetch_assoc()['c'];
$total_proposals = $db->query("SELECT COUNT(*) as c FROM job_proposal")->fetch_assoc()['c'];
$total_clients   = $db->query("SELECT COUNT(*) as c FROM client")->fetch_assoc()['c'];
$total_freelancers = $db->query("SELECT COUNT(*) as c FROM freelancer")->fetch_assoc()['c'];
$completed       = $db->query("SELECT COUNT(*) as c FROM job_proposal WHERE status='completed successfully'")->fetch_assoc()['c'];
$recent_logs     = $db->query("SELECT l.*, u.first_name, u.last_name FROM log l LEFT JOIN user u ON l.user_id=u.id ORDER BY l.time_created DESC LIMIT 10");
$pageTitle = 'Admin Dashboard';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_jobs"><i class="bi bi-briefcase"></i> Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_skills"><i class="bi bi-tags"></i> Skills</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_logs"><i class="bi bi-journal-text"></i> Logs</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title fade-up">Admin Dashboard</div>
    <div class="page-subtitle fade-up">Platform overview and activity</div>

    <div class="stat-grid fade-up-2">
        <div class="stat-card blue"><div class="stat-num"><?= $total_users ?></div><div class="stat-label">Total Users</div></div>
        <div class="stat-card green"><div class="stat-num"><?= $total_clients ?></div><div class="stat-label">Clients</div></div>
        <div class="stat-card pink"><div class="stat-num"><?= $total_freelancers ?></div><div class="stat-label">Freelancers</div></div>
        <div class="stat-card gold"><div class="stat-num"><?= $total_jobs ?></div><div class="stat-label">Total Jobs</div></div>
        <div class="stat-card cyan"><div class="stat-num"><?= $total_proposals ?></div><div class="stat-label">Proposals</div></div>
        <div class="stat-card green"><div class="stat-num"><?= $completed ?></div><div class="stat-label">Completed</div></div>
    </div>

    <div class="card fade-up-3">
        <div class="card-header"><span><i class="bi bi-journal-text" style="margin-right:8px;color:var(--accent)"></i>Recent Activity Logs</span></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>User</th><th>Type</th><th>Action</th><th>IP</th><th>Time</th></tr></thead>
                <tbody>
                <?php while($log = $recent_logs->fetch_assoc()): ?>
                <tr>
                    <td><?= $log['first_name'] ? htmlspecialchars($log['first_name'].' '.$log['last_name']) : '<em style="color:var(--muted)">Guest</em>' ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($log['type']) ?></span></td>
                    <td style="color:var(--muted);font-size:13px;"><?= htmlspecialchars(substr($log['action'],0,60)) ?></td>
                    <td><code><?= htmlspecialchars($log['ip_address']) ?></code></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d, H:i', strtotime($log['time_created'])) ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
