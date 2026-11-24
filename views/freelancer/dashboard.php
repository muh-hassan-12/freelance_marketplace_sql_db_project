<?php
require_once 'config/app.php';
requireRole('freelancer');
$db = getDB();
$fl = $db->prepare("SELECT f.*, u.first_name, u.last_name FROM freelancer f JOIN user u ON f.user_id=u.id WHERE f.user_id=?");
$fl->bind_param("i",$_SESSION['user_id']); $fl->execute();
$freelancer = $fl->get_result()->fetch_assoc();
$fl_id = $freelancer['id'];
$total_proposals = $db->query("SELECT COUNT(*) as c FROM job_proposal WHERE freelancer_id=$fl_id")->fetch_assoc()['c'];
$accepted = $db->query("SELECT COUNT(*) as c FROM job_proposal WHERE freelancer_id=$fl_id AND status='accepted'")->fetch_assoc()['c'];
$completed = $db->query("SELECT COUNT(*) as c FROM job_proposal WHERE freelancer_id=$fl_id AND status='completed successfully'")->fetch_assoc()['c'];
$available = $db->query("SELECT COUNT(*) as c FROM job WHERE is_active=1")->fetch_assoc()['c'];
$recent = $db->query("SELECT jp.*, j.title as job_title, j.pay_rate_per_hour FROM job_proposal jp JOIN job j ON jp.job_id=j.id WHERE jp.freelancer_id=$fl_id ORDER BY jp.time_created DESC LIMIT 5");
$pageTitle = 'Freelancer Dashboard';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Freelancer</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard" class="active"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=browse_jobs"><i class="bi bi-search"></i> Browse Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_proposals"><i class="bi bi-file-text"></i> My Proposals</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title fade-up">Welcome, <?= htmlspecialchars($freelancer['first_name']) ?>! 👋</div>
    <div class="page-subtitle fade-up">Your freelancer overview</div>
    <div class="stat-grid fade-up-2">
        <div class="stat-card blue"><div class="stat-num"><?= $available ?></div><div class="stat-label">Available Jobs</div></div>
        <div class="stat-card gold"><div class="stat-num"><?= $total_proposals ?></div><div class="stat-label">My Proposals</div></div>
        <div class="stat-card green"><div class="stat-num"><?= $accepted ?></div><div class="stat-label">Accepted</div></div>
        <div class="stat-card pink"><div class="stat-num"><?= $completed ?></div><div class="stat-label">Completed</div></div>
    </div>
    <div class="card fade-up-3">
        <div class="card-header">
            <span><i class="bi bi-file-text" style="margin-right:8px;color:var(--accent)"></i>Recent Proposals</span>
            <a href="<?= BASE_URL ?>/index.php?page=browse_jobs" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Find Jobs</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Job</th><th>Proposal</th><th>Rate</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                <?php while($p = $recent->fetch_assoc()):
                    $sb = ['sent'=>'badge-blue','accepted'=>'badge-green','rejected'=>'badge-red','withdrawn'=>'badge-gray','work submitted'=>'badge-yellow','completed successfully'=>'badge-green','completed unsuccessfully'=>'badge-red'];
                    $bc = $sb[$p['status']] ?? 'badge-gray';
                ?>
                <tr>
                    <td><?= htmlspecialchars($p['job_title']) ?></td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($p['title']) ?></td>
                    <td><span class="badge badge-blue">PKR <?= $p['pay_rate_per_hour'] ?>/hr</span></td>
                    <td><span class="badge <?= $bc ?>"><?= ucfirst($p['status']) ?></span></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d', strtotime($p['time_created'])) ?></td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
