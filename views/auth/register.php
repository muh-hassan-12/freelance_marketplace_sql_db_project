<?php $pageTitle = 'Register'; require_once 'views/shared/header.php';
$db = getDB();
$skills = $db->query("SELECT * FROM skill ORDER BY name");
$db->close();
?>
<div class="main-full" style="padding:40px 20px;min-height:calc(100vh - 60px);display:flex;align-items:center;justify-content:center;">
    <div style="width:100%;max-width:560px;background:var(--card-bg);border:1px solid var(--border);border-radius:20px;padding:40px;animation:fadeUp 0.5s ease;">
        <div class="auth-logo">
            <div class="icon">🚀</div>
            <h2>Create Account</h2>
            <p>Join the marketplace today</p>
        </div>
        <?php require_once 'views/shared/flash.php'; ?>
        <form method="POST" action="<?= BASE_URL ?>/index.php" id="regForm">
            <input type="hidden" name="action" value="register">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">First Name</label>
                    <input type="text" name="first_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="last_name" class="form-control" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="you@example.com">
            </div>
            <div class="form-group">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" required placeholder="03001234567">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="••••••••">
                </div>
                <div class="form-group">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required placeholder="••••••••">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Register As</label>
                <select name="role" class="form-select" id="roleSelect" required onchange="toggleRole()">
                    <option value="">— Select Role —</option>
                    <option value="client">Client (I want to hire)</option>
                    <option value="freelancer">Freelancer (I want to work)</option>
                </select>
            </div>

            <div id="clientFields" style="display:none;">
                <div class="form-group">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Tech Startup">
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" placeholder="About your company…"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="individual">Individual</option>
                        <option value="company">Company</option>
                    </select>
                </div>
            </div>

            <div id="freelancerFields" style="display:none;">
                <div class="form-group">
                    <label class="form-label">Professional Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Full Stack Developer">
                </div>
                <div class="form-group">
                    <label class="form-label">Bio</label>
                    <textarea name="description" class="form-control" placeholder="Tell clients about yourself…"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Years of Experience</label>
                    <input type="number" name="years_of_experience" class="form-control" min="0" value="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Skills</label>
                    <div style="background:var(--surface);border:1px solid var(--border);border-radius:10px;padding:12px;max-height:150px;overflow-y:auto;display:grid;grid-template-columns:1fr 1fr;gap:4px;">
                        <?php $skills->data_seek(0); while($sk = $skills->fetch_assoc()): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="skills[]" value="<?= $sk['id'] ?>" id="sk<?= $sk['id'] ?>">
                            <label class="form-check-label" for="sk<?= $sk['id'] ?>"><?= htmlspecialchars($sk['name']) ?></label>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="justify-content:center;padding:12px;">Create Account →</button>
        </form>
        <p style="text-align:center;font-size:14px;color:var(--muted);margin-top:18px;">
            Already have an account? <a href="<?= BASE_URL ?>/index.php?page=login">Sign in →</a>
        </p>
    </div>
</div>
<script>
function toggleRole() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('clientFields').style.display     = role === 'client'     ? 'block' : 'none';
    document.getElementById('freelancerFields').style.display = role === 'freelancer' ? 'block' : 'none';
}
</script>
<?php require_once 'views/shared/footer.php'; ?>
