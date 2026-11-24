<?php
require_once __DIR__ . '/../config/app.php';
requireAdmin();

$action = $_POST['action'] ?? '';
$db     = getDB();

if ($action === 'add_skill') {
    $name = sanitize($_POST['name'] ?? '');
    $stmt = $db->prepare("INSERT IGNORE INTO skill (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    setFlash('success', 'Skill added.');
    redirect('index.php?page=admin_skills');
}

if ($action === 'delete_skill') {
    $id   = intval($_POST['skill_id'] ?? 0);
    $stmt = $db->prepare("DELETE FROM skill WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    setFlash('success', 'Skill deleted.');
    redirect('index.php?page=admin_skills');
}

if ($action === 'toggle_user') {
    $id   = intval($_POST['user_id'] ?? 0);
    $stmt = $db->prepare("UPDATE `user` SET is_active = NOT is_active WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    setFlash('success', 'User status updated.');
    redirect('index.php?page=admin_users');
}

$db->close();
?>
