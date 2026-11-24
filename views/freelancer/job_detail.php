<?php
require_once 'config/app.php';
requireRole('freelancer');
$job_id = intval($_GET['id'] ?? 0);
$db = getDB();
$job = $db->query("SELECT j.*, u.first_name, u.last_name FROM job j JOIN client c ON j.client_id=c.id JOIN user u ON c.user_id=u.id WHERE j.id=$job_id")->fetch_assoc();
if (!$job) { setFlash('danger','Job not found.'); redirect('index.php?page=browse_jobs'); }
$skills = $db->query("SELECT s.name FROM job_skill js JOIN skill s ON js.skill_id=s.id WHERE js.job_id=$job_id");
$fl = $db->prepare("SELECT id FROM freelancer WHERE user_id=?");
$fl->bind_param("i",$_SESSION['user_id']); $fl->execute();
$freelancer = $fl->get_result()->fetch_assoc();
$already_applied = false;
if ($freelancer) {
    $chk = $db->query("SELECT id FROM job_proposal WHERE job_id=$job_id AND freelancer_id=".$freelancer['id']);
    $already_applied = $chk->num_rows > 0;
}
$pageTitle = $job['title'];
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Freelancer</div>
        <a href="<?= BASE_URL ?>/index.php?page=dashboard"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=browse_jobs" class="active"><i class="bi bi-search"></i> Browse Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=my_proposals"><i class="bi bi-file-text"></i> My Proposals</a>
        <a href="<?= BASE_URL ?>/index.php?page=profile"><i class="bi bi-person"></i> Profile</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <a href="<?= BASE_URL ?>/index.php?page=browse_jobs" class="btn btn-outline btn-sm mb-3"><i class="bi bi-arrow-left"></i> Back to Jobs</a>

    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">
        <!-- Left: Job Details -->
        <div class="card">
            <div class="card-body">
                <h2 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.4rem;margin-bottom:8px;"><?= htmlspecialchars($job['title']) ?></h2>
                <p style="color:var(--muted);font-size:13px;margin-bottom:20px;">
                    <i class="bi bi-person" style="margin-right:4px;"></i><?= htmlspecialchars($job['first_name'].' '.$job['last_name']) ?>
                    &nbsp;•&nbsp; Posted: <?= date('M d, Y', strtotime($job['time_created'])) ?>
                </p>
                <hr style="border-color:var(--border);margin-bottom:20px;">
                <p style="line-height:1.8;color:var(--text);"><?= nl2br(htmlspecialchars($job['description'])) ?></p>
                <?php
                $skill_list = [];
                while($sk = $skills->fetch_assoc()) $skill_list[] = $sk['name'];
                if (!empty($skill_list)):
                ?>
                <div style="margin-top:20px;">
                    <div style="font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--muted);margin-bottom:8px;">Required Skills</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <?php foreach($skill_list as $sk): ?>
                        <span class="badge badge-blue"><?= htmlspecialchars($sk) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right: Apply Card -->
        <div style="display:flex;flex-direction:column;gap:16px;">
            <div class="card">
                <div class="card-header">Job Details</div>
                <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--muted);font-size:13px;"><i class="bi bi-cash" style="margin-right:6px;"></i>Pay Rate</span>
                        <span class="badge badge-green">PKR <?= number_format($job['pay_rate_per_hour'],0) ?>/hr</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--muted);font-size:13px;"><i class="bi bi-clock" style="margin-right:6px;"></i>Duration</span>
                        <span style="font-weight:600;"><?= $job['expected_duration_in_hours'] ?> hrs</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--muted);font-size:13px;"><i class="bi bi-calculator" style="margin-right:6px;"></i>Total</span>
                        <span style="font-weight:700;color:var(--accent3);">PKR <?= number_format($job['pay_rate_per_hour']*$job['expected_duration_in_hours'],0) ?></span>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;">
                        <span style="color:var(--muted);font-size:13px;"><i class="bi bi-calendar" style="margin-right:6px;"></i>Deadline</span>
                        <span style="font-size:13px;"><?= date('M d, Y', strtotime($job['receive_job_proposals_deadline'])) ?></span>
                    </div>
                    <hr style="border-color:var(--border);">
                    <?php if ($already_applied): ?>
                    <div class="alert alert-success" style="margin:0;"><i class="bi bi-check-circle-fill"></i> Already Applied!</div>
                    <?php elseif($job['is_active']): ?>
                    <button class="btn btn-primary w-100" style="justify-content:center;" onclick="document.getElementById('applyModal').style.display='flex'">
                        <i class="bi bi-send"></i> Apply Now
                    </button>
                    <?php else: ?>
                    <div class="alert" style="background:rgba(122,122,154,0.1);color:var(--muted);border:1px solid var(--border);margin:0;">This job is closed.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
<div id="applyModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:2000;align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--card-bg);border:1px solid var(--border);border-radius:20px;width:100%;max-width:520px;padding:32px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
            <h3 style="font-family:'Syne',sans-serif;font-weight:700;font-size:1.2rem;margin:0;">Submit Proposal</h3>
            <button onclick="document.getElementById('applyModal').style.display='none'" style="background:var(--surface2);border:none;color:var(--muted);width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:16px;">✕</button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="action" value="submit_proposal">
            <input type="hidden" name="job_id" value="<?= $job_id ?>">
            <div class="form-group">
                <label class="form-label">Proposal Title</label>
                <input type="text" name="title" class="form-control" required placeholder="e.g. I can build this in 3 days">
            </div>
            <div class="form-group">
                <label class="form-label">Cover Letter</label>
                <textarea name="description" class="form-control" rows="5" required placeholder="Why are you the best fit for this job?"></textarea>
            </div>
            <div style="display:flex;gap:10px;margin-top:20px;">
                <button type="button" onclick="document.getElementById('applyModal').style.display='none'" class="btn btn-outline" style="flex:1;justify-content:center;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;"><i class="bi bi-send"></i> Submit</button>
            </div>
        </form>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
