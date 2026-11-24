<?php
// rating_controller.php
require_once __DIR__ . '/../config/app.php';
requireLogin();

$job_id  = intval($_POST['job_id'] ?? 0);
$type    = sanitize($_POST['type'] ?? '');
$rating  = intval($_POST['rating'] ?? 0);
$comment = sanitize($_POST['comment'] ?? '');

if ($rating < 1 || $rating > 5) { setFlash('danger', 'Rating must be between 1 and 5.'); redirect('index.php?page=dashboard'); }

$db   = getDB();
$stmt = $db->prepare("INSERT INTO job_rating (job_id, type, rating, comment) VALUES (?, ?, ?, ?) ON DUPLICATE KEY UPDATE rating=VALUES(rating), comment=VALUES(comment)");
$stmt->bind_param("isis", $job_id, $type, $rating, $comment);
if ($stmt->execute()) {
    setFlash('success', 'Rating submitted successfully!');
} else {
    setFlash('danger', 'Failed to submit rating.');
}
$db->close();

if ($type === 'freelancer') redirect('index.php?page=my_jobs');
redirect('index.php?page=my_proposals');
?>
