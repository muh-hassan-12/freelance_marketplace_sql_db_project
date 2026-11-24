<?php
require_once 'config/app.php';
requireRole('client');
$job_id = intval($_GET['job_id'] ?? 0);
$db = getDB();
$job = $db->query("SELECT * FROM job WHERE id=$job_id")->fetch_assoc();
if (!$job) { setFlash('danger','Job not found.'); redirect('index.php?page=my_jobs'); }
$proposals = $db->query("SELECT jp.*, f.id as fid, f.title as ftitle, f.years_of_experience, u.first_name, u.last_name, u.email FROM job_proposal jp JOIN freelancer f ON jp.freelancer_id=f.id JOIN user u ON f.user_id=u.id WHERE jp.job_id=$job_id ORDER BY jp.time_created DESC");
$pageTitle = 'Job Proposals';
require_once 'views/shared/header.php';
$status_badges = ['sent'=>'badge-blue','accepted'=>'badge-green','rejected'=>'badge-red','withdrawn'=>'badge-gray','work submitted'=>'badge-yellow','completed successfully'=>'badge-green','completed unsuccessfully'=>'badge-red'];
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
    <a href="<?= BASE_URL ?>/index.php?page=my_jobs" class="btn btn-outline btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back to My Jobs</a>
    <div class="page-title"><?= htmlspecialchars($job['title']) ?></div>
    <div class="page-subtitle">PKR <?= number_format($job['pay_rate_per_hour'],0) ?>/hr &nbsp;•&nbsp; Deadline: <?= date('M d, Y', strtotime($job['receive_job_proposals_deadline'])) ?></div>

    <?php
    $all = [];
    while($p = $proposals->fetch_assoc()) $all[] = $p;
    if(empty($all)):
    ?>
    <div class="empty-state"><i class="bi bi-people"></i><p>No proposals received yet.</p></div>
    <?php else: ?>
    <div style="display:flex;flex-direction:column;gap:14px;">
    <?php foreach($all as $p):
        $bc = $status_badges[$p['status']] ?? 'badge-gray';
    ?>
    <div class="card">
        <div class="card-body">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:12px;margin-bottom:14px;">
                <div>
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:4px;">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--accent),var(--accent2));border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:14px;"><?= strtoupper(substr($p['first_name'],0,1)) ?></div>
                        <div>
                            <div style="font-weight:700;"><?= htmlspecialchars($p['first_name'].' '.$p['last_name']) ?></div>
                            <div style="font-size:12px;color:var(--muted);"><?= htmlspecialchars($p['ftitle']) ?> &nbsp;•&nbsp; <?= $p['years_of_experience'] ?> yrs exp</div>
                        </div>
                    </div>
                </div>
                <span class="badge <?= $bc ?>"><?= ucfirst($p['status']) ?></span>
            </div>
            <div style="background:var(--surface);border-radius:10px;padding:14px;margin-bottom:14px;">
                <div style="font-weight:600;margin-bottom:6px;font-size:14px;"><?= htmlspecialchars($p['title']) ?></div>
                <p style="color:var(--muted);font-size:13px;margin:0;line-height:1.6;"><?= nl2br(htmlspecialchars($p['description'])) ?></p>
            </div>
            <?php if($p['submission_description']): ?>
            <div class="alert alert-success" style="margin-bottom:14px;"><i class="bi bi-upload" style="margin-right:6px;"></i><strong>Work Submitted:</strong><br><?= nl2br(htmlspecialchars($p['submission_description'])) ?></div>
            <?php endif; ?>
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <?php if($p['status'] === 'sent'): ?>
                <form method="POST" action="<?= BASE_URL ?>/index.php">
                    <input type="hidden" name="action" value="accept_proposal">
                    <input type="hidden" name="proposal_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="job_id" value="<?= $job_id ?>">
                    <button class="btn btn-success btn-sm"><i class="bi bi-check"></i> Accept</button>
                </form>
                <form method="POST" action="<?= BASE_URL ?>/index.php">
                    <input type="hidden" name="action" value="reject_proposal">
                    <input type="hidden" name="proposal_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="job_id" value="<?= $job_id ?>">
                    <button class="btn btn-danger btn-sm"><i class="bi bi-x"></i> Reject</button>
                </form>
                <?php elseif($p['status'] === 'work submitted'): ?>
                <form method="POST" action="<?= BASE_URL ?>/index.php" style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
                    <input type="hidden" name="action" value="complete_proposal">
                    <input type="hidden" name="proposal_id" value="<?= $p['id'] ?>">
                    <input type="hidden" name="job_id" value="<?= $job_id ?>">
                    <input type="text" name="client_comment" class="form-control" placeholder="Comment (optional)" style="width:200px;padding:7px 12px;">
                    <select name="result" class="form-select" style="width:200px;padding:7px 12px;">
                        <option value="completed successfully">✓ Mark Completed</option>
                        <option value="completed unsuccessfully">✗ Mark Unsuccessful</option>
                    </select>
                    <button class="btn btn-primary btn-sm">Submit Review</button>
                </form>
                <?php elseif($p['status'] === 'completed successfully'): ?>
                <a href="<?= BASE_URL ?>/index.php?page=rate_freelancer&job_id=<?= $job_id ?>&freelancer_id=<?= $p['fid'] ?>" class="btn btn-sm" style="background:rgba(245,158,11,0.15);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);"><i class="bi bi-star"></i> Rate Freelancer</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
