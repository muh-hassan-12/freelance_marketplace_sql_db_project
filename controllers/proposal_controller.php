<?php
require_once __DIR__ . '/../config/app.php';
requireLogin();

$action = $_POST['action'] ?? '';
$db     = getDB();

// ── SUBMIT PROPOSAL (Freelancer) ──────────────────────────────────────────────
if ($action === 'submit_proposal') {
    requireRole('freelancer');
    $job_id = intval($_POST['job_id'] ?? 0);
    $title  = sanitize($_POST['title'] ?? '');
    $desc   = sanitize($_POST['description'] ?? '');

    $fl = $db->prepare("SELECT id FROM freelancer WHERE user_id = ?");
    $fl->bind_param("i", $_SESSION['user_id']);
    $fl->execute();
    $freelancer = $fl->get_result()->fetch_assoc();

    if (!$freelancer) { setFlash('danger', 'Freelancer profile not found.'); redirect('index.php?page=browse_jobs'); }

    // Check already applied
    $chk = $db->prepare("SELECT id FROM job_proposal WHERE job_id = ? AND freelancer_id = ?");
    $chk->bind_param("ii", $job_id, $freelancer['id']);
    $chk->execute();
    if ($chk->get_result()->num_rows > 0) {
        setFlash('warning', 'You have already applied for this job.');
        redirect('index.php?page=job_detail&id=' . $job_id);
    }

    $stmt = $db->prepare("INSERT INTO job_proposal (job_id, freelancer_id, title, description) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $job_id, $freelancer['id'], $title, $desc);
    if ($stmt->execute()) {
        setFlash('success', 'Proposal submitted successfully!');
    } else {
        setFlash('danger', 'Failed to submit proposal.');
    }
    redirect('index.php?page=my_proposals');
}

// ── WITHDRAW PROPOSAL (Freelancer) ────────────────────────────────────────────
if ($action === 'withdraw_proposal') {
    requireRole('freelancer');
    $proposal_id = intval($_POST['proposal_id'] ?? 0);
    $fl = $db->prepare("SELECT id FROM freelancer WHERE user_id = ?");
    $fl->bind_param("i", $_SESSION['user_id']);
    $fl->execute();
    $freelancer = $fl->get_result()->fetch_assoc();

    $stmt = $db->prepare("UPDATE job_proposal SET status = 'withdrawn' WHERE id = ? AND freelancer_id = ?");
    $stmt->bind_param("ii", $proposal_id, $freelancer['id']);
    $stmt->execute();
    setFlash('success', 'Proposal withdrawn.');
    redirect('index.php?page=my_proposals');
}

// ── ACCEPT PROPOSAL (Client) ──────────────────────────────────────────────────
if ($action === 'accept_proposal') {
    requireRole('client');
    $proposal_id = intval($_POST['proposal_id'] ?? 0);
    $job_id      = intval($_POST['job_id'] ?? 0);
    $now         = date('Y-m-d H:i:s');
    $end         = date('Y-m-d H:i:s', strtotime('+7 days'));

    $stmt = $db->prepare("UPDATE job_proposal SET status = 'accepted', time_work_starts = ?, time_work_ends = ? WHERE id = ?");
    $stmt->bind_param("ssi", $now, $end, $proposal_id);
    $stmt->execute();

    // Reject all others for same job
    $rej = $db->prepare("UPDATE job_proposal SET status = 'rejected' WHERE job_id = ? AND id != ? AND status = 'sent'");
    $rej->bind_param("ii", $job_id, $proposal_id);
    $rej->execute();

    setFlash('success', 'Proposal accepted! Work starts now.');
    redirect('index.php?page=job_proposals&job_id=' . $job_id);
}

// ── REJECT PROPOSAL (Client) ──────────────────────────────────────────────────
if ($action === 'reject_proposal') {
    requireRole('client');
    $proposal_id = intval($_POST['proposal_id'] ?? 0);
    $job_id      = intval($_POST['job_id'] ?? 0);
    $stmt = $db->prepare("UPDATE job_proposal SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $proposal_id);
    $stmt->execute();
    setFlash('success', 'Proposal rejected.');
    redirect('index.php?page=job_proposals&job_id=' . $job_id);
}

// ── SUBMIT WORK (Freelancer) ──────────────────────────────────────────────────
if ($action === 'submit_work') {
    requireRole('freelancer');
    $proposal_id = intval($_POST['proposal_id'] ?? 0);
    $sub_desc    = sanitize($_POST['submission_description'] ?? '');
    $stmt = $db->prepare("UPDATE job_proposal SET status = 'work submitted', submission_description = ? WHERE id = ?");
    $stmt->bind_param("si", $sub_desc, $proposal_id);
    $stmt->execute();
    setFlash('success', 'Work submitted successfully!');
    redirect('index.php?page=my_proposals');
}

// ── COMPLETE PROPOSAL (Client) ────────────────────────────────────────────────
if ($action === 'complete_proposal') {
    requireRole('client');
    $proposal_id = intval($_POST['proposal_id'] ?? 0);
    $job_id      = intval($_POST['job_id'] ?? 0);
    $result      = sanitize($_POST['result'] ?? 'completed successfully');
    $comment     = sanitize($_POST['client_comment'] ?? '');

    $stmt = $db->prepare("UPDATE job_proposal SET status = ?, client_comment = ? WHERE id = ?");
    $stmt->bind_param("ssi", $result, $comment, $proposal_id);
    $stmt->execute();
    setFlash('success', 'Job marked as ' . $result . '!');
    redirect('index.php?page=job_proposals&job_id=' . $job_id);
}

$db->close();
?>
