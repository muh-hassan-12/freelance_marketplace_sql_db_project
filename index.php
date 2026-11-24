<?php
require_once 'config/app.php';

$page = isset($_GET['page']) ? sanitize($_GET['page']) : 'home';

// Route map
$routes = [
    // Public
    'home'              => 'views/shared/home.php',
    'login'             => 'views/auth/login.php',
    'register'          => 'views/auth/register.php',
    'logout'            => 'controllers/auth_controller.php',
    'forgot_password'   => 'views/auth/forgot_password.php',
    'reset_password'    => 'views/auth/reset_password.php',

    // Shared
    'dashboard'         => 'views/shared/dashboard.php',
    'profile'           => 'views/shared/profile.php',

    // Client
    'post_job'          => 'views/client/post_job.php',
    'my_jobs'           => 'views/client/my_jobs.php',
    'job_proposals'     => 'views/client/job_proposals.php',
    'rate_freelancer'   => 'views/client/rate_freelancer.php',
    'payment'           => 'views/client/payment.php',

    // Freelancer
    'browse_jobs'       => 'views/freelancer/browse_jobs.php',
    'job_detail'        => 'views/freelancer/job_detail.php',
    'my_proposals'      => 'views/freelancer/my_proposals.php',
    'submit_work'       => 'views/freelancer/submit_work.php',
    'rate_client'       => 'views/freelancer/rate_client.php',

    // Admin
    'admin'             => 'views/admin/dashboard.php',
    'admin_users'       => 'views/admin/users.php',
    'admin_jobs'        => 'views/admin/jobs.php',
    'admin_logs'        => 'views/admin/logs.php',
    'admin_skills'      => 'views/admin/skills.php',
];

// Handle controller actions (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    switch($action) {
        case 'login':
        case 'logout':
        case 'register':
        case 'forgot_password':
        case 'reset_password':
            require_once 'controllers/auth_controller.php';
            exit();
        case 'post_job':
        case 'delete_job':
        case 'toggle_job':
            require_once 'controllers/job_controller.php';
            exit();
        case 'submit_proposal':
        case 'withdraw_proposal':
        case 'accept_proposal':
        case 'reject_proposal':
        case 'submit_work':
        case 'complete_proposal':
            require_once 'controllers/proposal_controller.php';
            exit();
        case 'submit_rating':
            require_once 'controllers/rating_controller.php';
            exit();
        case 'submit_payment':
            require_once 'controllers/payment_controller.php';
            exit();
        case 'update_profile':
            require_once 'controllers/profile_controller.php';
            exit();
        case 'add_skill':
        case 'delete_skill':
        case 'toggle_user':
            require_once 'controllers/admin_controller.php';
            exit();
    }
}

if (array_key_exists($page, $routes)) {
    require_once $routes[$page];
} else {
    require_once 'views/shared/404.php';
}
?>
