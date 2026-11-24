<?php $pageTitle = 'Home'; require_once 'views/shared/header.php'; ?>

<div class="main-full" style="padding:0;">
    <!-- HERO -->
    <div class="hero">
        <div style="position:relative;z-index:1;">
            <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(108,99,255,0.1);border:1px solid rgba(108,99,255,0.3);border-radius:20px;padding:6px 16px;font-size:13px;color:var(--accent);margin-bottom:24px;">
                <i class="bi bi-stars"></i> Pakistan's #1 Freelance Platform
            </div>
            <h1 class="hero-title">
                Connect. Hire.<br><span class="grad">Build Together.</span>
            </h1>
            <p class="hero-sub">Find top freelancers or land your dream projects. Secure payments, real results.</p>
            <div class="hero-btns">
                <a href="<?= BASE_URL ?>/index.php?page=register" class="btn btn-primary btn-hero">Start for Free →</a>
                <a href="<?= BASE_URL ?>/index.php?page=browse_jobs" class="btn btn-outline btn-hero">Browse Jobs</a>
            </div>
        </div>
    </div>

    <!-- FEATURES -->
    <div class="feature-grid">
        <div class="feature-card fade-up">
            <div class="feature-icon" style="background:rgba(108,99,255,0.15);color:var(--accent)"><i class="bi bi-briefcase-fill"></i></div>
            <h4>Post a Job</h4>
            <p>Describe your project and receive proposals from skilled freelancers within hours.</p>
        </div>
        <div class="feature-card fade-up-2">
            <div class="feature-icon" style="background:rgba(67,233,123,0.15);color:var(--accent3)"><i class="bi bi-people-fill"></i></div>
            <h4>Hire Talent</h4>
            <p>Browse verified freelancer profiles, review proposals and pick the best fit for your project.</p>
        </div>
        <div class="feature-card fade-up-3">
            <div class="feature-icon" style="background:rgba(255,101,132,0.15);color:var(--accent2)"><i class="bi bi-shield-fill-check"></i></div>
            <h4>Secure Payments</h4>
            <p>Pay safely once the work is delivered and you're completely satisfied with the result.</p>
        </div>
    </div>

    <!-- LATEST JOBS -->
    <?php
        $db = getDB();
        $jobs = $db->query("SELECT j.*, u.first_name, u.last_name FROM job j JOIN client c ON j.client_id=c.id JOIN user u ON c.user_id=u.id WHERE j.is_active=1 ORDER BY j.time_created DESC LIMIT 6");
    ?>
    <div class="jobs-section">
        <h2 class="section-title">Latest Jobs</h2>
        <div class="job-grid">
        <?php while($job = $jobs->fetch_assoc()): ?>
            <div class="job-card">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:10px;">
                    <h5 style="font-weight:600;font-size:15px;"><?= htmlspecialchars($job['title']) ?></h5>
                    <span class="badge badge-blue">PKR <?= number_format($job['pay_rate_per_hour'],0) ?>/hr</span>
                </div>
                <p style="color:var(--muted);font-size:13px;margin-bottom:14px;"><?= htmlspecialchars(substr($job['description'],0,100)) ?>…</p>
                <div style="display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:12px;color:var(--muted)"><i class="bi bi-person me-1"></i><?= htmlspecialchars($job['first_name'].' '.$job['last_name']) ?></span>
                    <a href="<?= BASE_URL ?>/index.php?page=job_detail&id=<?= $job['id'] ?>" class="btn btn-outline btn-sm">View →</a>
                </div>
            </div>
        <?php endwhile; $db->close(); ?>
        </div>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
