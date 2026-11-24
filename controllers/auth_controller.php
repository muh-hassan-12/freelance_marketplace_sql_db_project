<?php
require_once __DIR__ . '/../config/app.php';

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['page']) ? $_GET['page'] : '');

// ── LOGOUT ────────────────────────────────────────────────────────────────────
if ($action === 'logout' || isset($_GET['page']) && $_GET['page'] === 'logout') {
    if (isLoggedIn()) {
        logAction('Log Out', 'User logged out');
    }
    session_destroy();
    redirect('index.php?page=login');
}

// ── LOGIN ─────────────────────────────────────────────────────────────────────
if ($action === 'login') {
    $email    = sanitize($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        setFlash('danger', 'Please fill in all fields.');
        redirect('index.php?page=login');
    }

    $db   = getDB();
    $stmt = $db->prepare("SELECT * FROM `user` WHERE email = ? AND is_active = 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['first_name'] = $user['first_name'];
        $_SESSION['last_name']  = $user['last_name'];
        $_SESSION['email']      = $user['email'];
        $_SESSION['is_admin']   = $user['is_admin'];

        // Determine role
        if ($user['is_admin']) {
            $_SESSION['role'] = 'admin';
        } else {
            $c = $db->prepare("SELECT id FROM client WHERE user_id = ?");
            $c->bind_param("i", $user['id']);
            $c->execute();
            if ($c->get_result()->num_rows > 0) {
                $_SESSION['role'] = 'client';
            } else {
                $f = $db->prepare("SELECT id FROM freelancer WHERE user_id = ?");
                $f->bind_param("i", $user['id']);
                $f->execute();
                if ($f->get_result()->num_rows > 0) {
                    $_SESSION['role'] = 'freelancer';
                } else {
                    $_SESSION['role'] = 'none';
                }
            }
        }

        logAction('Log In', 'User logged in: ' . $email);
        $db->close();

        if ($user['is_admin']) redirect('index.php?page=admin');
        redirect('index.php?page=dashboard');
    } else {
        setFlash('danger', 'Invalid email or password.');
        redirect('index.php?page=login');
    }
}

// ── REGISTER ──────────────────────────────────────────────────────────────────
if ($action === 'register') {
    $first_name  = sanitize($_POST['first_name'] ?? '');
    $last_name   = sanitize($_POST['last_name'] ?? '');
    $email       = sanitize($_POST['email'] ?? '');
    $phone       = sanitize($_POST['phone'] ?? '');
    $password    = $_POST['password'] ?? '';
    $confirm     = $_POST['confirm_password'] ?? '';
    $role        = sanitize($_POST['role'] ?? '');
    $title       = sanitize($_POST['title'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $experience  = intval($_POST['years_of_experience'] ?? 0);
    $type        = sanitize($_POST['type'] ?? 'individual');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($phone) || empty($password) || empty($role)) {
        setFlash('danger', 'Please fill in all required fields.');
        redirect('index.php?page=register');
    }

    if ($password !== $confirm) {
        setFlash('danger', 'Passwords do not match.');
        redirect('index.php?page=register');
    }

    if (strlen($password) < 6) {
        setFlash('danger', 'Password must be at least 6 characters.');
        redirect('index.php?page=register');
    }

    $db = getDB();

    // Check duplicate email
    $check = $db->prepare("SELECT id FROM `user` WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    if ($check->get_result()->num_rows > 0) {
        setFlash('danger', 'Email already registered.');
        redirect('index.php?page=register');
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt   = $db->prepare("INSERT INTO `user` (email, phone, password, first_name, last_name) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $email, $phone, $hashed, $first_name, $last_name);

    if ($stmt->execute()) {
        $user_id = $db->insert_id;

        if ($role === 'client') {
            $cs = $db->prepare("INSERT INTO client (user_id, title, description, type) VALUES (?, ?, ?, ?)");
            $cs->bind_param("isss", $user_id, $title, $description, $type);
            $cs->execute();
            logAction('Create Client', 'New client registered: ' . $email);
        } elseif ($role === 'freelancer') {
            $fs = $db->prepare("INSERT INTO freelancer (user_id, title, description, years_of_experience) VALUES (?, ?, ?, ?)");
            $fs->bind_param("issi", $user_id, $title, $description, $experience);
            $fs->execute();

            // Add skills
            if (!empty($_POST['skills'])) {
                $fl_id = $db->insert_id;
                foreach ($_POST['skills'] as $skill_id) {
                    $ss = $db->prepare("INSERT IGNORE INTO freelancer_skill (freelancer_id, skill_id) VALUES (?, ?)");
                    $ss->bind_param("ii", $fl_id, $skill_id);
                    $ss->execute();
                }
            }
            logAction('Create Freelancer', 'New freelancer registered: ' . $email);
        }

        setFlash('success', 'Registration successful! Please login.');
        $db->close();
        redirect('index.php?page=login');
    } else {
        setFlash('danger', 'Registration failed. Please try again.');
        redirect('index.php?page=register');
    }
}

// ── FORGOT PASSWORD ───────────────────────────────────────────────────────────
if ($action === 'forgot_password') {
    $email = sanitize($_POST['email'] ?? '');
    $db    = getDB();
    $stmt  = $db->prepare("SELECT id FROM `user` WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user) {
        $token   = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $ins     = $db->prepare("INSERT INTO reset_password_token (user_id, token, time_expires) VALUES (?, ?, ?)");
        $ins->bind_param("iss", $user['id'], $token, $expires);
        $ins->execute();
        setFlash('success', 'Reset link generated. Use token: ' . $token . ' on the reset page.');
    } else {
        setFlash('danger', 'Email not found.');
    }
    $db->close();
    redirect('index.php?page=forgot_password');
}

// ── RESET PASSWORD ────────────────────────────────────────────────────────────
if ($action === 'reset_password') {
    $token    = sanitize($_POST['token'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        setFlash('danger', 'Passwords do not match.');
        redirect('index.php?page=reset_password');
    }

    $db   = getDB();
    $now  = date('Y-m-d H:i:s');
    $stmt = $db->prepare("SELECT * FROM reset_password_token WHERE token = ? AND time_expires > ?");
    $stmt->bind_param("ss", $token, $now);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $upd    = $db->prepare("UPDATE `user` SET password = ? WHERE id = ?");
        $upd->bind_param("si", $hashed, $row['user_id']);
        $upd->execute();
        $del = $db->prepare("DELETE FROM reset_password_token WHERE user_id = ?");
        $del->bind_param("i", $row['user_id']);
        $del->execute();
        setFlash('success', 'Password reset successfully. Please login.');
        redirect('index.php?page=login');
    } else {
        setFlash('danger', 'Invalid or expired token.');
        redirect('index.php?page=reset_password');
    }
}
?>
