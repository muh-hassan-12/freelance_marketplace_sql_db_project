<?php
require_once 'config/app.php';
requireAdmin();
$db = getDB();
$users = $db->query("SELECT u.*, 
    CASE WHEN u.is_admin=1 THEN 'admin' WHEN c.id IS NOT NULL THEN 'client' WHEN f.id IS NOT NULL THEN 'freelancer' ELSE 'none' END as role
    FROM user u 
    LEFT JOIN client c ON c.user_id=u.id 
    LEFT JOIN freelancer f ON f.user_id=u.id 
    ORDER BY u.time_created DESC");
$pageTitle = 'Manage Users';
require_once 'views/shared/header.php';
?>
<div class="sidebar">
    <div class="sidebar-section">
        <div class="sidebar-label">Admin Panel</div>
        <a href="<?= BASE_URL ?>/index.php?page=admin"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_users" class="active"><i class="bi bi-people"></i> Users</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_jobs"><i class="bi bi-briefcase"></i> Jobs</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_skills"><i class="bi bi-tags"></i> Skills</a>
        <a href="<?= BASE_URL ?>/index.php?page=admin_logs"><i class="bi bi-journal-text"></i> Logs</a>
    </div>
    <div class="sidebar-section">
        <a href="<?= BASE_URL ?>/index.php?page=logout" class="danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
    </div>
</div>
<div class="main">
    <?php require_once 'views/shared/flash.php'; ?>
    <div class="page-title">Users</div>
    <div class="page-subtitle">All registered users on the platform</div>
    <div class="card">
        <div class="table-wrap">
            <table>
                <thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Joined</th><th>Action</th></tr></thead>
                <tbody>
                <?php while($u = $users->fetch_assoc()): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($u['first_name'].' '.$u['last_name']) ?></strong></td>
                    <td style="color:var(--muted)"><?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php
                        $rb = ['admin'=>'badge-blue','client'=>'badge-cyan','freelancer'=>'badge-green','none'=>'badge-gray'];
                        echo '<span class="badge '.($rb[$u['role']]??'badge-gray').'">'.ucfirst($u['role']).'</span>';
                        ?>
                    </td>
                    <td><?= $u['is_active'] ? '<span class="badge badge-green">Active</span>' : '<span class="badge badge-red">Blocked</span>' ?></td>
                    <td style="color:var(--muted);font-size:13px;"><?= date('M d, Y', strtotime($u['time_created'])) ?></td>
                    <td>
                        <?php if(!$u['is_admin']): ?>
                        <form method="POST" action="<?= BASE_URL ?>/index.php" style="display:inline;">
                            <input type="hidden" name="action" value="toggle_user">
                            <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                            <button class="btn btn-sm <?= $u['is_active'] ? 'btn-danger' : 'btn-success' ?>">
                                <?= $u['is_active'] ? 'Block' : 'Unblock' ?>
                            </button>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $db->close(); require_once 'views/shared/footer.php'; ?>
