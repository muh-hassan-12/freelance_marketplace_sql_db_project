<?php
require_once __DIR__ . '/../config/app.php';
requireRole('client');

$job_id = intval($_POST['job_id'] ?? 0);
$phone  = sanitize($_POST['phone_number'] ?? '');
$amount = floatval($_POST['amount'] ?? 0);
$db     = getDB();

$stmt = $db->prepare("INSERT INTO job_payment (job_id, phone_number, amount, is_payment_successful, response_merchant_request_id, response_checkout_request_id) VALUES (?, ?, ?, 1, 'SIMULATED', 'SIMULATED')");
$stmt->bind_param("isd", $job_id, $phone, $amount);
if ($stmt->execute()) {
    $pay_id = $db->insert_id;
    // Simulate dispatch
    $dis = $db->prepare("INSERT INTO job_payment_dispatch (job_payment_id, phone_number, amount, is_dispatch_successful, response_conversation_id, response_originator_conversation_id) VALUES (?, ?, ?, 1, 'SIM_CONV', 'SIM_ORIG')");
    $dis->bind_param("isd", $pay_id, $phone, $amount);
    $dis->execute();
    setFlash('success', 'Payment of PKR ' . $amount . ' simulated successfully to ' . $phone . '!');
} else {
    setFlash('danger', 'Payment failed.');
}
$db->close();
redirect('index.php?page=my_jobs');
?>
