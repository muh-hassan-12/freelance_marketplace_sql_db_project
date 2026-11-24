<?php
session_start();
require_once __DIR__ . '/database.php';

define('BASE_URL', 'http://localhost/freelance_marketplace');
define('SITE_NAME', 'Freelance Marketplace');

// ── Auth helpers ─────────────────────────────────────────────────────────────
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function isClient() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'client';
}

function isFreelancer() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'freelancer';
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('index.php?page=login');
    }
}

function requireAdmin() {
    if (!isAdmin()) {
        redirect('index.php?page=login');
    }
}

function requireRole($role) {
    requireLogin();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $role) {
        redirect('index.php?page=dashboard');
    }
}

// ── Redirect ──────────────────────────────────────────────────────────────────
function redirect($url) {
    header("Location: " . BASE_URL . "/" . $url);
    exit();
}

// ── Flash messages ─────────────────────────────────────────────────────────────
function setFlash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

// ── Sanitize input ────────────────────────────────────────────────────────────
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// ── Log action ───────────────────────────────────────────────────────────────
function logAction($type, $action) {
    $db = getDB();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    if ($user_id) {
        $stmt = $db->prepare("INSERT INTO `log` (user_id, type, action, ip_address) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $type, $action, $ip);
    } else {
        $stmt = $db->prepare("INSERT INTO `log` (type, action, ip_address) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $type, $action, $ip);
    }
    $stmt->execute();
    $db->close();
}
?>
