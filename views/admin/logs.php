<?php
require_once 'config/app.php';
requireAdmin();
$db = getDB();
$logs = $db->query("SELECT l.*, u.first_name, u.last_name FROM log l LEFT JOIN user u ON l.user_id=u.id ORDER BY l.time_created DESC LIMIT 100");
$pageTitle = 'Activity Logs';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_jobs"><i class="bi bi-briefcase"></i> Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_skills"><i class="bi bi-tags"></i> Skills</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_logs" class="active"><i class="bi bi-journal-text"></i> Logs</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <div class="page-title">Activity Logs</div>
    <div class="page-subtitle">Last 100 platform events</div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>User</th><th>Type</th><th>Action</th><th>IP</th><th>Time</th></tr></thead>
                <tbody>
                <?php while($log = $logs->fetch_assoc()): ?>
                <tr>
                    <td><?= $log['first_name'] ? htmlspecialchars($log['first_name'].' '.$log['last_name']) : '<em style="color:var(--muted)">Guest</em>' ?></td>
                    <td><span class="badge badge-blue"><?= htmlspecialchars($log['type']) ?></span></td>
                    <td style="color:var(--muted);font-size:13px;"><?= htmlspecialchars(substr($log['action'],0,80)) ?></td>
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
