<?php $pageTitle = 'Reset Password'; require_once 'views/shared/header.php'; ?>
<div class="main-full auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <div class="icon">🔒</div>
            <h2>New Password</h2>
            <p>Enter your token and new password</p>
        </div>
        <?php require_once 'views/shared/flash.php'; ?>
        <form method="POST" action="<?= BASE_URL ?>/index.php">
            <input type="hidden" name="action" value="reset_password">
            <div class="form-group">
                <label class="form-label">Reset Token</label>
                <input type="text" name="token" class="form-control" required placeholder="Paste token here">
            </div>
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required placeholder="••••••••">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
            </div>
            <button type="submit" class="btn btn-primary w-100" style="justify-content:center;padding:12px;">Reset Password</button>
        </form>
    </div>
</div>
<?php require_once 'views/shared/footer.php'; ?>
