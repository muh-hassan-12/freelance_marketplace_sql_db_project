<?php
require_once 'config/app.php';
requireRole('freelancer');
$db = getDB();
$fl = $db->prepare("SELECT id FROM freelancer WHERE user_id=?");
$fl->bind_param("i",$_SESSION['user_id']); $fl->execute();
$freelancer = $fl->get_result()->fetch_assoc();
$fl_id = $freelancer['id'];
$proposals = $db->query("SELECT jp.*, j.title as job_title, j.pay_rate_per_hour, j.expected_duration_in_hours, j.id as jid FROM job_proposal jp JOIN job j ON jp.job_id=j.id WHERE jp.freelancer_id=$fl_id ORDER BY jp.time_created DESC");
$pageTitle = 'My Proposals';
require_once 'views/shared/header.php';
$status_badges = ['sent'=>'badge-blue','accepted'=>'badge-green','rejected'=>'badge-red','withdrawn'=>'badge-gray','work submitted'=>'badge-yellow','completed successfully'=>'badge-green','completed unsuccessfully'=>'badge-red','job expired before work submission'=>'badge-gray'];
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
    <div class="page-title">My Proposals</div>
    <div class="page-subtitle">Track all your submitted proposals</div>

    <?php
    $all = [];
    while($p = $proposals->fetch_assoc()) $all[] = $p;
    if (empty($all)):
    ?>
    <div class="empty-state"><i class="bi bi-file-text"></i><p>No proposals yet. <a href="<?= BASE_URL ?>/index.php?page=browse_jobs">Browse jobs →</a></p></div>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:14px;">
    <?php foreach($all as $p):
        $bc = $status_badges[$p['status']] ?? 'badge-gray';
    ?>
    <div class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:12px;">
                <div>
                    <h5 style="font-weight:700;font-size:15px;margin-bottom:4px;"><?= htmlspecialchars($p['job_title']) ?></h5>
                    <p style="color:var(--muted);font-size:13px;margin:0;">Proposal: <?= htmlspecialchars($p['title']) ?></p>
                </div>
                <span class="badge <?= $bc ?>"><?= ucfirst($p['status']) ?></span>
            </div>
            <div style="display:flex;gap:16px;margin-bottom:12px;">
                <span style="font-size:13px;color:var(--muted);"><i class="bi bi-cash" style="margin-right:4px;"></i>PKR <?= number_format($p['pay_rate_per_hour'],0) ?>/hr</span>
                <span style="font-size:13px;color:var(--muted);"><i class="bi bi-clock" style="margin-right:4px;"></i><?= $p['expected_duration_in_hours'] ?> hrs</span>
                <span style="font-size:13px;color:var(--accent3);font-weight:600;">Total: PKR <?= number_format($p['pay_rate_per_hour']*$p['expected_duration_in_hours'],0) ?></span>
            </div>
            <?php if($p['client_comment']): ?>
            <div class="alert alert-info" style="margin-bottom:12px;"><i class="bi bi-chat-left-text" style="margin-right:6px;"></i><strong>Client:</strong> <?= htmlspecialchars($p['client_comment']) ?></div>
            <?php endif; ?>
            <?php if($p['submission_description']): ?>
            <div class="alert" style="background:rgba(67,233,123,0.08);border:1px solid rgba(67,233,123,0.2);color:var(--accent3);margin-bottom:12px;"><i class="bi bi-upload" style="margin-right:6px;"></i><strong>Submitted:</strong> <?= htmlspecialchars(substr($p['submission_description'],0,100)) ?>…</div>
            <?php endif; ?>
            <div style="display:flex;gap:8px;">
                <?php if($p['status'] === 'sent'): ?>
                <form method="POST" action="<?= BASE_URL ?>/index.php">
                    <input type="hidden" name="action" value="withdraw_proposal">
                    <input type="hidden" name="proposal_id" value="<?= $p['id'] ?>">
                    <button class="btn btn-danger btn-sm" onclick="return confirm('Withdraw this proposal?')"><i class="bi bi-x"></i> Withdraw</button>
                </form>
                <?php elseif($p['status'] === 'accepted'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=submit_work&proposal_id=<?= $p['id'] ?>" class="btn btn-success btn-sm"><i class="bi bi-upload"></i> Submit Work</a>
                <?php elseif($p['status'] === 'completed successfully'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=rate_client&job_id=<?= $p['jid'] ?>" class="btn btn-sm" style="background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);"><i class="bi bi-star"></i> Rate Client</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
