<?php $pageTitle = 'Login'; require_once 'views/shared/header.php'; ?>
<div class="main-full auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="icon">⚡</div>
            <h2>Welcome back</h2>
            <p>Sign in to your account</p>
        </div>
        <?php require_once 'views/shared/flash.php'; ?>
        <form method="POST" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100" style="justify-content:center;padding:12px;">Sign In →</button>
        </form>
        <div class="auth-divider"><span>or</span></div>
        <p style="text-align:center;font-size:14px;color:var(--muted);">
            <a href="<?= BASE_URL ?>/index.php?page=forgot_password">Forgot password?</a>
        </p>
        <p style="text-align:center;font-size:14px;color:var(--muted);margin-top:12px;">
            No account? <a href="<?= BASE_URL ?>/index.php?page=register">Create one →</a>
        </p>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
