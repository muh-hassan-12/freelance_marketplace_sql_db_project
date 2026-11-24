<?php
require_once __DIR__ . '/../config/app.php';
requireLogin();

$first_name = sanitize($_POST['first_name'] ?? '');
$last_name  = sanitize($_POST['last_name'] ?? '');
$phone      = sanitize($_POST['phone'] ?? '');
$city       = sanitize($_POST['city'] ?? '');
$country    = sanitize($_POST['country'] ?? '');
$title      = sanitize($_POST['title'] ?? '');
$description= sanitize($_POST['description'] ?? '');

$db   = getDB();
$stmt = $db->prepare("UPDATE `user` SET first_name=?, last_name=?, phone=?, city=?, country=? WHERE id=?");
$stmt->bind_param("sssssi", $first_name, $last_name, $phone, $city, $country, $_SESSION['user_id']);
$stmt->execute();

// Update role-specific profile
if (isClient()) {
    $cl = $db->prepare("UPDATE client SET title=?, description=? WHERE user_id=?");
    $cl->bind_param("ssi", $title, $description, $_SESSION['user_id']);
    $cl->execute();
} elseif (isFreelancer()) {
    $experience = intval($_POST['years_of_experience'] ?? 0);
    $fl = $db->prepare("UPDATE freelancer SET title=?, description=?, years_of_experience=? WHERE user_id=?");
    $fl->bind_param("ssii", $title, $description, $experience, $_SESSION['user_id']);
    $fl->execute();

    // Update skills
    $fl2 = $db->prepare("SELECT id FROM freelancer WHERE user_id=?");
    $fl2->bind_param("i", $_SESSION['user_id']);
    $fl2->execute();
    $flRow = $fl2->get_result()->fetch_assoc();
    if ($flRow && !empty($_POST['skills'])) {
        $del = $db->prepare("DELETE FROM freelancer_skill WHERE freelancer_id=?");
        $del->bind_param("i", $flRow['id']);
        $del->execute();
        foreach ($_POST['skills'] as $skill_id) {
            $ins = $db->prepare("INSERT IGNORE INTO freelancer_skill (freelancer_id, skill_id) VALUES (?,?)");
            $ins->bind_param("ii", $flRow['id'], $skill_id);
            $ins->execute();
        }
    }
}

$_SESSION['first_name'] = $first_name;
$_SESSION['last_name']  = $last_name;
setFlash('success', 'Profile updated successfully!');
$db->close();
redirect('index.php?page=profile');
?>
