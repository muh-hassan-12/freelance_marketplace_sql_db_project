<?php
require_once __DIR__ . '/../config/app.php';
requireRole('client');

$action = $_POST['action'] ?? '';
$db = getDB();

// Get client id
$cl = $db->prepare("SELECT id FROM client WHERE user_id = ?");
$cl->bind_param("i", $_SESSION['user_id']);
$cl->execute();
$client = $cl->get_result()->fetch_assoc();

if (!$client) { setFlash('danger', 'Client profile not found.'); redirect('index.php?page=dashboard'); }
$client_id = $client['id'];

// ── POST JOB ──────────────────────────────────────────────────────────────────
if ($action === 'post_job') {
    $title    = sanitize($_POST['title'] ?? '');
    $desc     = sanitize($_POST['description'] ?? '');
    $rate     = floatval($_POST['pay_rate_per_hour'] ?? 0);
    $duration = floatval($_POST['expected_duration_in_hours'] ?? 0);
    $deadline = sanitize($_POST['deadline'] ?? '');
    $skills   = $_POST['skills'] ?? [];

    if (empty($title) || empty($desc) || empty($deadline)) {
        setFlash('danger', 'Please fill all required fields.');
        redirect('index.php?page=post_job');
    }

    $stmt = $db->prepare("INSERT INTO job (client_id, title, description, pay_rate_per_hour, expected_duration_in_hours, receive_job_proposals_deadline) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issdds", $client_id, $title, $desc, $rate, $duration, $deadline);

    if ($stmt->execute()) {
        $job_id = $db->insert_id;
        foreach ($skills as $skill_id) {
            $ss = $db->prepare("INSERT IGNORE INTO job_skill (job_id, skill_id) VALUES (?, ?)");
            $ss->bind_param("ii", $job_id, $skill_id);
            $ss->execute();
        }
        logAction('General Log', 'Job posted: ' . $title);
        setFlash('success', 'Job posted successfully!');
    } else {
        setFlash('danger', 'Failed to post job.');
    }
    redirect('index.php?page=my_jobs');
}

// ── DELETE JOB ────────────────────────────────────────────────────────────────
if ($action === 'delete_job') {
    $job_id = intval($_POST['job_id'] ?? 0);
    $stmt   = $db->prepare("DELETE FROM job WHERE id = ? AND client_id = ?");
    $stmt->bind_param("ii", $job_id, $client_id);
    $stmt->execute();
    setFlash('success', 'Job deleted.');
    redirect('index.php?page=my_jobs');
}

// ── TOGGLE JOB ────────────────────────────────────────────────────────────────
if ($action === 'toggle_job') {
    $job_id = intval($_POST['job_id'] ?? 0);
    $stmt   = $db->prepare("UPDATE job SET is_active = NOT is_active WHERE id = ? AND client_id = ?");
    $stmt->bind_param("ii", $job_id, $client_id);
    $stmt->execute();
    setFlash('success', 'Job status updated.');
    redirect('index.php?page=my_jobs');
}

$db->close();
?>
