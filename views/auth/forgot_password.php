<?php $pageTitle = 'Forgot Password'; require_once 'views/shared/header.php'; ?>
<div class="main-full auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="icon">🔑</div>
            <h2>Reset Password</h2>
            <p>Enter your email to get a reset token</p>
        </div>
        <?php require_once 'views/shared/flash.php'; ?>
        <form method="POST" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="action" value="forgot_password">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>
            <button type="submit" class="btn btn-primary w-100" style="justify-content:center;padding:12px;">Send Reset Token</button>
        </form>
        <p style="text-align:center;font-size:14px;color:var(--muted);margin-top:18px;">
            <a href="<?= BASE_URL ?>/index.php?page=login">← Back to Login</a>
        </p>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
