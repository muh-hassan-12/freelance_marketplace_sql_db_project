<?php $pageTitle = '404'; require_once 'views/shared/header.php'; ?>
<div class="main-full" style="display:flex;align-items:center;justify-content:center;min-height:80vh;text-align:center;">
    <div>
        <div style="font-family:'Syne',sans-serif;font-size:6rem;font-weight:800;color:var(--border);line-height:1;">404</div>
        <h2 style="font-family:'Syne',sans-serif;margin-bottom:12px;">Page not found</h2>
        <p style="color:var(--muted);margin-bottom:24px;">The page you're looking for doesn't exist.</p>
        <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Go Home</a>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
