<?php
// api/otp/verify-email-otp.php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');
$code  = trim($_POST['code']  ?? '');

if ($email === '' || $code === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Missing email or code.'
    ]);
    exit;
}

$sessionEmail   = $_SESSION['otp_email']   ?? null;
$sessionCode    = $_SESSION['otp_code']    ?? null;
$sessionExpires = $_SESSION['otp_expires'] ?? null;

if (!$sessionEmail || !$sessionCode || !$sessionExpires) {
    echo json_encode([
        'success' => false,
        'message' => 'No active verification code. Please request a new one.'
    ]);
    exit;
}

if (time() > (int)$sessionExpires) {
    echo json_encode([
        'success' => false,
        'message' => 'Your code has expired. Please request a new one.'
    ]);
    exit;
}

if (strcasecmp($email, (string)$sessionEmail) !== 0) {
    echo json_encode([
        'success' => false,
        'message' => 'This code does not match the email used.'
    ]);
    exit;
}

if ($code !== (string)$sessionCode) {
    echo json_encode([
        'success' => false,
        'message' => 'Incorrect code. Please try again.'
    ]);
    exit;
}

// Mark as verified
$_SESSION['otp_verified'] = true;

// Clear only the code & expiry so it can't be reused
// but KEEP otp_email so order-confirmed.php can match it
unset($_SESSION['otp_code'], $_SESSION['otp_expires']);

echo json_encode([
    'success' => true,
    'message' => 'Code verified.'
]);
