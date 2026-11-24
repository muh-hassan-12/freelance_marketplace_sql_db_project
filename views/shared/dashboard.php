<?php
require_once 'config/app.php';
requireLogin();
if (isAdmin()) {
    require_once 'views/admin/dashboard.php';
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'client') {
    require_once 'views/client/dashboard.php';
} elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'freelancer') {
    require_once 'views/freelancer/dashboard.php';
} else {
    $pageTitle = 'Dashboard';
    require_once 'views/shared/header.php';
    echo '<div class="main"><div class="page-title">Dashboard</div><p style="color:var(--muted)">Your account role is not set up. Please contact admin.</p></div>';
    require_once 'views/shared/footer.php';
}
