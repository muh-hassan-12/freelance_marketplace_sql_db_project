<?php
require_once 'config/app.php';
requireRole('freelancer');
$db = getDB();
$search = sanitize($_GET['search'] ?? '');
$sql = "SELECT j.*, u.first_name, u.last_name, c.type as client_type,
        (SELECT GROUP_CONCAT(s.name SEPARATOR ',') FROM job_skill js JOIN skill s ON js.skill_id=s.id WHERE js.job_id=j.id) as skills
        FROM job j JOIN client c ON j.client_id=c.id JOIN user u ON c.user_id=u.id WHERE j.is_active=1";
if ($search) $sql .= " AND (j.title LIKE '%$search%' OR j.description LIKE '%$search%')";
$sql .= " ORDER BY j.time_created DESC";
$jobs = $db->query($sql);
$pageTitle = 'Browse Jobs';
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
    <div class="page-title fade-up">Browse Jobs</div>
    <div class="page-subtitle fade-up">Find your next project</div>

    <!-- Search -->
    <form method="GET" style="margin-bottom:24px;" class="fade-up">
        <input type="hidden" name="page" value="browse_jobs">
        <div style="display:flex;gap:10px;">
            <input type="text" name="search" class="form-control" placeholder="Search jobs by title or keyword..." value="<?= htmlspecialchars($search) ?>" style="flex:1;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> Search</button>
            <?php if($search): ?>
            <a href="<?= BASE_URL ?>/index.php?page=browse_jobs" class="btn btn-outline">Clear</a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Jobs Grid -->
    <?php
    $all_jobs = [];
    while($job = $jobs->fetch_assoc()) $all_jobs[] = $job;
    if (empty($all_jobs)):
    ?>
    <div class="empty-state fade-up">
        <i class="bi bi-briefcase"></i>
        <p><?= $search ? 'No jobs found for "'.$search.'"' : 'No jobs available right now. Check back later!' ?></p>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:16px;">
    <?php foreach($all_jobs as $job): ?>
        <div class="job-card fade-up" style="display:flex;flex-direction:column;gap:12px;">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;">
                <h5 style="font-weight:700;font-size:15px;margin:0;"><?= htmlspecialchars($job['title']) ?></h5>
                <span class="badge badge-blue" style="white-space:nowrap;">PKR <?= number_format($job['pay_rate_per_hour'],0) ?>/hr</span>
            </div>
            <p style="color:var(--muted);font-size:13px;margin:0;line-height:1.6;"><?= htmlspecialchars(substr($job['description'],0,130)) ?>…</p>
            <?php if($job['skills']): ?>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                <?php foreach(explode(',',$job['skills']) as $sk): ?>
                <span class="badge badge-gray"><?= htmlspecialchars(trim($sk)) ?></span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:8px;border-top:1px solid var(--border);">
                <div>
                    <div style="font-size:12px;color:var(--muted);"><i class="bi bi-person" style="margin-right:4px;"></i><?= htmlspecialchars($job['first_name'].' '.$job['last_name']) ?></div>
                    <div style="font-size:12px;color:var(--muted);margin-top:2px;"><i class="bi bi-clock" style="margin-right:4px;"></i>Deadline: <?= date('M d, Y', strtotime($job['receive_job_proposals_deadline'])) ?></div>
                    <div style="font-size:12px;color:var(--muted);margin-top:2px;"><i class="bi bi-hourglass" style="margin-right:4px;"></i><?= $job['expected_duration_in_hours'] ?> hrs expected</div>
                </div>
                <a href="<?= BASE_URL ?>/index.php?page=job_detail&id=<?= $job['id'] ?>" class="btn btn-primary btn-sm">View & Apply →</a>
            </div>
        </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
