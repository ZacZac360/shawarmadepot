<?php
// api/otp/send-email-otp.php
session_start();
header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit;
}

$email = trim($_POST['email'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email address.'
    ]);
    exit;
}

// Generate 6-digit OTP
try {
    $otp = random_int(100000, 999999);
} catch (Exception $e) {
    // Fallback if random_int fails for some reason
    $otp = mt_rand(100000, 999999);
}

// Store OTP in session (expires in 5 minutes)
$_SESSION['otp_email']   = $email;
$_SESSION['otp_code']    = (string)$otp;
$_SESSION['otp_expires'] = time() + 300; // 5 minutes
$_SESSION['otp_verified'] = false;

// ---------------------- Brevo API config ----------------------
$BREVO_API_KEY  = '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';
$SENDER_EMAIL   = 'crispino.zyrus@gmail.com';
$SENDER_NAME    = 'Shawarma Depot';

// Safety: trim in case of copy-paste spaces
$BREVO_API_KEY = trim($BREVO_API_KEY);


$payload = [
    'sender' => [
        'email' => $SENDER_EMAIL,
        'name'  => $SENDER_NAME
    ],
    'to' => [
        ['email' => $email]
    ],
    'subject' => 'Your Shawarma Depot confirmation code',
    'htmlContent' => '<html><body>' .
        '<p>Hi!</p>' .
        '<p>Your Shawarma Depot confirmation code is: <strong>' . $otp . '</strong></p>' .
        '<p>This code will expire in 5 minutes.</p>' .
        '</body></html>'
];

// Call Brevo API
$ch = curl_init('https://api.brevo.com/v3/smtp/email');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'accept: application/json',
    'content-type: application/json',
    'api-key: ' . $BREVO_API_KEY
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr  = curl_error($ch);
curl_close($ch);

// DEBUG ONLY – REMOVE LATER
file_put_contents(__DIR__ . '/brevo_debug.log', 
    "Key (first 12 chars): " . substr($BREVO_API_KEY, 0, 12) . "...\n" .
    "HTTP $httpCode\n$response\n\n", 
    FILE_APPEND
);




if ($response === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Failed to contact email service: ' . $curlErr
    ]);
    exit;
}

if ($httpCode < 200 || $httpCode >= 300) {
    // You can log $response if you want
    echo json_encode([
        'success' => false,
        'message' => 'Email service returned an error (HTTP ' . $httpCode . ').'
    ]);
    exit;
}

// All good
echo json_encode([
    'success' => true,
    'message' => 'OTP sent to ' . $email
]);
