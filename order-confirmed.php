<?php
// order-confirmed.php

session_start();

// DB connection
$host = "localhost";
$user = "root";
$pass = "Pokemon2003";      // your password
$db   = "shawarma_depot";  // your DB name

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Helper: build tracking URL (reused for page + email)
function buildTrackUrl($order_code, $customer_phone) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $dir    = rtrim(dirname($_SERVER['REQUEST_URI'] ?? ''), '/\\');

    return $scheme . '://' . $host . $dir .
        '/track-order.php?order_code=' . urlencode($order_code) .
        '&order_phone=' . urlencode($customer_phone);
}

// Helper: send order confirmation email via Brevo
function sendOrderConfirmationEmail($email, $name, $order_code, $cart_items, $subtotal, $delivery_fee, $total_amount, $track_url) {
    if (!$email) {
        return; // nothing to do
    }

    // Same Brevo config as send-email-otp.php
    $BREVO_API_KEY  = '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';
    $SENDER_EMAIL   = 'crispino.zyrus@gmail.com';
    $SENDER_NAME    = 'Shawarma Depot';

    $BREVO_API_KEY = trim($BREVO_API_KEY);

    // Build items table HTML
    $rowsHtml = '';
    if (is_array($cart_items) && !empty($cart_items)) {
        foreach ($cart_items as $item) {
            $nameItem = htmlspecialchars($item['name'] ?? 'Item', ENT_QUOTES, 'UTF-8');
            $summary  = htmlspecialchars($item['summary'] ?? '', ENT_QUOTES, 'UTF-8');
            $qty      = (int)($item['qty'] ?? 0);
            $unit     = (float)($item['unitPrice'] ?? 0);
            $line     = $qty * $unit;

            $rowsHtml .= '
                <tr>
                    <td style="padding:4px 8px;border:1px solid #ddd;">' . $nameItem . '</td>
                    <td style="padding:4px 8px;border:1px solid #ddd;font-size:12px;color:#666;">' . $summary . '</td>
                    <td style="padding:4px 8px;border:1px solid #ddd;text-align:right;">' . $qty . '</td>
                    <td style="padding:4px 8px;border:1px solid #ddd;text-align:right;">₱' . number_format($unit, 2) . '</td>
                    <td style="padding:4px 8px;border:1px solid #ddd;text-align:right;">₱' . number_format($line, 2) . '</td>
                </tr>
            ';
        }
    } else {
        $rowsHtml = '
            <tr>
                <td colspan="5" style="padding:8px;border:1px solid #ddd;text-align:center;color:#666;font-size:13px;">
                    No items were recorded for this order.
                </td>
            </tr>
        ';
    }

    $nameSafe = htmlspecialchars($name ?: 'Customer', ENT_QUOTES, 'UTF-8');
    $orderCodeSafe = htmlspecialchars($order_code, ENT_QUOTES, 'UTF-8');
    $trackUrlSafe = htmlspecialchars($track_url, ENT_QUOTES, 'UTF-8');

    $htmlContent = '
    <html>
    <body style="font-family: Arial, sans-serif; background-color:#f5f5f5; padding:20px;">
        <div style="max-width:600px;margin:0 auto;background:#ffffff;border-radius:8px;padding:20px;border:1px solid #eee;">
            <h2 style="margin-top:0;color:#333;">Thank you for your order, ' . $nameSafe . '!</h2>
            <p style="font-size:14px;color:#555;">
                Your shawarma order has been received and is now in the queue.
            </p>

            <p style="font-size:14px;color:#555;">
                <strong>Tracking code:</strong>
                <span style="display:inline-block;background:#222;color:#ffb300;padding:4px 8px;border-radius:4px;font-family:monospace;">
                    ' . $orderCodeSafe . '
                </span>
            </p>

            <p style="font-size:14px;color:#555;">
                You can track this order online here:<br>
                <a href="' . $trackUrlSafe . '" style="color:#ff9800;" target="_blank">' . $trackUrlSafe . '</a>
            </p>

            <h3 style="font-size:16px;margin-top:24px;border-top:1px solid #eee;padding-top:12px;">Order summary</h3>

            <table style="width:100%;border-collapse:collapse;font-size:13px;margin-bottom:12px;">
                <thead>
                    <tr style="background:#f9f9f9;">
                        <th style="padding:6px 8px;border:1px solid #ddd;text-align:left;">Item</th>
                        <th style="padding:6px 8px;border:1px solid #ddd;text-align:left;">Details</th>
                        <th style="padding:6px 8px;border:1px solid #ddd;text-align:right;">Qty</th>
                        <th style="padding:6px 8px;border:1px solid #ddd;text-align:right;">Unit</th>
                        <th style="padding:6px 8px;border:1px solid #ddd;text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    ' . $rowsHtml . '
                </tbody>
            </table>

            <div style="text-align:right;font-size:14px;">
                <div><span style="color:#666;">Subtotal:</span> <strong>₱' . number_format($subtotal, 2) . '</strong></div>';

    if ($delivery_fee > 0) {
        $htmlContent .= '
                <div><span style="color:#666;">Delivery fee:</span> <strong>₱' . number_format($delivery_fee, 2) . '</strong></div>';
    }

    $htmlContent .= '
                <div style="margin-top:4px;font-size:15px;">
                    <span style="color:#333;">Total:</span>
                    <strong style="color:#2e7d32;">₱' . number_format($total_amount, 2) . '</strong>
                </div>
            </div>

            <p style="font-size:12px;color:#888;margin-top:24px;">
                If you have any questions about this order, you can reply to this email or reach us via our Facebook page.
            </p>
        </div>
    </body>
    </html>
    ';

    $payload = [
        'sender' => [
            'email' => $SENDER_EMAIL,
            'name'  => $SENDER_NAME
        ],
        'to' => [
            ['email' => $email]
        ],
        'subject' => 'Your Shawarma Depot order ' . $orderCodeSafe,
        'htmlContent' => $htmlContent
    ];

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
}


// Helper: generate tracking code
function generateOrderCode($length = 10) {
    $chars = "ABCDEFGHJKLMNPQRSTUVWXYZ23456789";
    $code  = "";
    $max   = strlen($chars) - 1;
    for ($i = 0; $i < $length; $i++) {
        $code .= $chars[random_int(0, $max)];
    }
    return $code;
}

// Defaults so HTML doesn't explode
$order_code           = "";
$customer_name        = "";
$customer_phone       = "";
$customer_messenger   = "";
$customer_email       = "";
$fulfillment_mode     = "";
$delivery_subdivision = "";
$delivery_address     = "";
$delivery_landmark    = "";
$payment_method       = "";
$order_notes          = "";

$subtotal      = 0.0;
$delivery_fee  = 0.0;
$total_amount  = 0.0;

$cart_json     = "[]";
$cart_items    = [];
$insert_ok     = false;
$error_msg     = "";

// Determine where order data comes from
$isPaymongoReturn = (
    $_SERVER['REQUEST_METHOD'] === 'GET'
    && isset($_GET['via'])
    && $_GET['via'] === 'paymongo'
    && isset($_SESSION['pending_order'])
);

// Default: data from POST (COD flow)
$data = $_POST;

// PayMongo return: use data saved in session
if ($isPaymongoReturn) {
    $data = $_SESSION['pending_order'];
}


// POST REQUEST: create order
if ($_SERVER["REQUEST_METHOD"] === "POST" || $isPaymongoReturn) {

    $customer_name        = trim($data['customer_name']        ?? '');
    $customer_phone       = trim($data['customer_phone']       ?? '');
    $customer_messenger   = trim($data['customer_messenger']   ?? '');
    $customer_email       = trim($data['customer_email']       ?? '');
    $fulfillment_mode     = trim($data['fulfillment_mode']     ?? '');
    $delivery_subdivision = trim($data['delivery_subdivision'] ?? '');
    $delivery_address     = trim($data['delivery_address']     ?? '');
    $delivery_landmark    = trim($data['delivery_landmark']    ?? '');
    $payment_method       = trim($data['payment_method']       ?? '');
    $order_notes          = trim($data['order_notes']          ?? '');

    $subtotal      = floatval($data['subtotal']      ?? 0);
    $delivery_fee  = floatval($data['delivery_fee']  ?? 0);
    $total_amount  = floatval($data['total_amount']  ?? 0);

    $cart_json = $data['cart_json'] ?? '[]';
    if ($cart_json === "") {
        $cart_json = "[]";
    }

    // Decode cart for display if needed
    $cart_items = json_decode($cart_json, true);
    if (!is_array($cart_items)) {
        $cart_items = [];
    }

    // Basic required fields (matches NOT NULL cols)
    if ($customer_name === "" || $customer_phone === "" || $fulfillment_mode === "") {
        $error_msg = "Missing required fields (name, phone, or fulfillment mode).";
    } else {

        // OTP verification required ONLY if COD
        if ($payment_method === "cod") {
            $otpVerified = $_SESSION['otp_verified'] ?? false;
            $otpEmail    = $_SESSION['otp_email'] ?? null;

            if (!$otpVerified) {
                $error_msg = "OTP verification is required for cash orders. Please go back and enter the code.";
            } elseif ($customer_email && $otpEmail && strcasecmp($otpEmail, $customer_email) !== 0) {
                // Only complain if both emails exist and don't match
                $error_msg = "The OTP was not verified for this email address.";
            }
        }

        // Only proceed with DB insert if no error so far
        if ($error_msg === "") {

            // Generate a unique tracking code for this order
            $order_code = generateOrderCode();

            $stmt = $mysqli->prepare("
                INSERT INTO orders (
                    order_code,
                    customer_name,
                    customer_phone,
                    customer_messenger,
                    customer_email,
                    fulfillment_mode,
                    delivery_subdivision,
                    delivery_address,
                    delivery_landmark,
                    payment_method,
                    order_notes,
                    subtotal,
                    delivery_fee,
                    total_amount,
                    cart_json
                ) VALUES (
                    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
                )
            ");

            if (!$stmt) {
                $error_msg = "Prepare failed: " . $mysqli->error;
            } else {
                $stmt->bind_param(
                    "sssssssssssddds",
                    $order_code,
                    $customer_name,
                    $customer_phone,
                    $customer_messenger,
                    $customer_email,
                    $fulfillment_mode,
                    $delivery_subdivision,
                    $delivery_address,
                    $delivery_landmark,
                    $payment_method,
                    $order_notes,
                    $subtotal,
                    $delivery_fee,
                    $total_amount,
                    $cart_json
                );

                if ($stmt->execute()) {
                    $insert_ok = true;

                    // Get the auto-increment ID of the order we just inserted
                    $order_id = $stmt->insert_id;

                    // Insert each cart item into order_items
                    if (!empty($cart_items) && $order_id) {
                        $itemStmt = $mysqli->prepare("
                            INSERT INTO order_items (
                                order_id,
                                product_key,
                                product_name,
                                summary,
                                unit_price,
                                quantity,
                                line_total,
                                raw_json
                            ) VALUES (
                                ?, ?, ?, ?, ?, ?, ?, ?
                            )
                        ");

                        if ($itemStmt) {
                            foreach ($cart_items as $item) {
                                $product_key  = $item["key"]      ?? null;
                                $product_name = $item["name"]     ?? "Item";
                                $summary      = $item["summary"]  ?? "";
                                $qty          = isset($item["qty"]) ? (int)$item["qty"] : 0;
                                $unit         = isset($item["unitPrice"]) ? (float)$item["unitPrice"] : 0.0;
                                $line_total   = $unit * $qty;
                                $raw_json     = json_encode($item, JSON_UNESCAPED_UNICODE);

                                $itemStmt->bind_param(
                                    "isssdids",
                                    $order_id,
                                    $product_key,
                                    $product_name,
                                    $summary,
                                    $unit,
                                    $qty,
                                    $line_total,
                                    $raw_json
                                );

                                $itemStmt->execute(); // ignore per-item errors for now
                            }
                            $itemStmt->close();
                        }
                    }

                    $stmt->close();

                // Build tracking URL for email
                $emailTrackUrl = buildTrackUrl($order_code, $customer_phone);

                // Send order confirmation email (if customer_email is present)
                if (!empty($customer_email)) {
                    sendOrderConfirmationEmail(
                        $customer_email,
                        $customer_name,
                        $order_code,
                        $cart_items,
                        $subtotal,
                        $delivery_fee,
                        $total_amount,
                        $emailTrackUrl
                    );
                }

                    // Clear OTP session after success
                    unset($_SESSION['otp_email'], $_SESSION['otp_code'], $_SESSION['otp_expires'], $_SESSION['otp_verified']);

                    // IMPORTANT: POST → Redirect → GET
                    header(
                        "Location: order-confirmed.php?code=" .
                        urlencode($order_code) .
                        "&phone=" . urlencode($customer_phone)
                    );
                    exit;

                } else {
                    $error_msg = "Execute failed: " . $stmt->error;
                    $stmt->close();
                }
            }
        } // end if no error
    } // end basic fields check

// GET REQUEST: show existing order
} else {
    $code  = trim($_GET['code']  ?? '');
    $phone = trim($_GET['phone'] ?? '');

    if ($code !== '' && $phone !== '') {
        $stmt = $mysqli->prepare("
            SELECT *
            FROM orders
            WHERE order_code = ?
              AND customer_phone = ?
            LIMIT 1
        ");

        if ($stmt) {
            $stmt->bind_param("ss", $code, $phone);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
            }
            $stmt->close();
        }

        if (!empty($row)) {
            // We have an order; treat as "insert ok" for display purposes
            $insert_ok           = true;

            $order_code           = $row['order_code']           ?? '';
            $customer_name        = $row['customer_name']        ?? '';
            $customer_phone       = $row['customer_phone']       ?? '';
            $customer_messenger   = $row['customer_messenger']   ?? '';
            $customer_email       = $row['customer_email']       ?? '';
            $fulfillment_mode     = $row['fulfillment_mode']     ?? '';
            $delivery_subdivision = $row['delivery_subdivision'] ?? '';
            $delivery_address     = $row['delivery_address']     ?? '';
            $delivery_landmark    = $row['delivery_landmark']    ?? '';
            $payment_method       = $row['payment_method']       ?? '';
            $order_notes          = $row['order_notes']          ?? '';

            $subtotal      = (float)($row['subtotal']      ?? 0);
            $delivery_fee  = (float)($row['delivery_fee']  ?? 0);
            $total_amount  = (float)($row['total_amount']  ?? 0);

            $cart_json  = $row['cart_json'] ?? '[]';
            $cart_items = json_decode($cart_json, true);
            if (!is_array($cart_items)) {
                $cart_items = [];
            }

        } else {
            $error_msg = "We couldn't find that order. It may have been removed.";
        }

    } else {
        $error_msg = "This page is only available right after placing an order.";
    }
}



// Build tracking URL + QR code URL (only if we have a valid order)
$track_url = "";
$qr_url    = "";

if ($insert_ok && $order_code && $customer_phone) {
    $track_url = buildTrackUrl($order_code, $customer_phone);

    // External QR microservice (goQR / qrserver)
    $qr_url = 'https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' .
        urlencode($track_url);
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Order Confirmed | Shawarma Depot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          crossorigin="anonymous">
    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex flex-column min-vh-100"><!-- IMPORTANT: make page full height -->

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="assets/images/logo.png" alt="Shawarma Depot Logo" width="50" height="50">
            <div class="ms-3 d-flex flex-column lh-1">
                <strong>Shawarma</strong>
                <span>Depot</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="track-order.php">Track Order</a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-warning fw-semibold px-3" href="menu.php">
                        <i class="fa-solid fa-cart-shopping me-1"></i> Cart
                    </a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-warning fw-semibold px-3" href="menu.php">
                        <i class="fa-solid fa-utensils me-1"></i> Order Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1"><!-- this stretches to fill remaining height -->
    <section class="py-5 section-info">
        <div class="container">
            <!-- Heading -->
            <div class="mb-4 text-center text-md-start">
                <div class="section-title mb-1 text-uppercase">
                    Order Status
                </div>
                <h1 class="section-heading h3 mb-2">
                    <?php if ($insert_ok): ?>
                        Thank you for your order!
                    <?php else: ?>
                        We couldn't finalize your order
                    <?php endif; ?>
                </h1>
                <p class="text-muted mb-0">
                    <?php if ($insert_ok): ?>
                        Your shawarma is now in the queue. Save your tracking code below to follow its status.
                    <?php else: ?>
                        Something went wrong while saving your order. You can try again or contact us if the issue persists.
                    <?php endif; ?>
                </p>
            </div>

            <?php if ($insert_ok): ?>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <!-- Confirmation summary / tracking card -->
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-success-subtle text-success d-inline-flex align-items-center justify-content-center me-3"
                                         style="width: 44px; height: 44px;">
                                        <i class="fa-solid fa-check fa-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="h5 mb-1">Order placed successfully</h2>
                                        <p class="small text-muted mb-0">
                                            We’re preparing your shawarma. You’ll hear from us soon.
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <p class="small text-muted mb-1">Your tracking code</p>
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-dark text-warning me-2">
                                        <span class="font-monospace">
                                            <?php echo htmlspecialchars($order_code); ?>
                                        </span>
                                    </span>
                                </div>

                                <p class="small text-muted mb-2">
                                    Use this code on the <a href="track-order.php">Track Order</a> page
                                    to see updates.
                                </p>

                                <div class="mt-4 d-grid gap-2">
                                    <a href="track-order.php?order_code=<?php echo urlencode($order_code); ?>&order_phone=<?php echo urlencode($customer_phone); ?>"
                                        class="btn btn-outline-dark btn-sm">
                                        <i class="fa-solid fa-truck-fast me-1"></i>
                                        Track this order
                                    </a>
                                    <a href="menu.php" class="btn btn-warning btn-sm text-dark fw-semibold">
                                        <i class="fa-solid fa-utensils me-1"></i>
                                        Back to Menu
                                    </a>
                                </div>

                                <?php if (!empty($qr_url) && !empty($track_url)): ?>
                                    <hr class="my-3">
                                    <div class="text-center">
                                        <img src="<?php echo htmlspecialchars($qr_url); ?>"
                                            alt="QR code to track this order"
                                            class="img-fluid border rounded p-1 bg-white"
                                            style="max-width: 220px;">
                                        <p class="small text-muted mt-2 mb-0">
                                            You may also san this QR to view your tracking page<br>
                                        </p>
                                    </div>
                                <?php endif; ?>

                            </div>
                        </div>
                    </div>

                    <!-- Receipt -->
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h2 class="h5 mb-0">
                                        <i class="fa-solid fa-receipt me-2 text-warning"></i>
                                        Order Receipt
                                    </h2>
                                    <span class="badge bg-dark text-warning">
                                        <?php echo htmlspecialchars(ucfirst($fulfillment_mode)); ?>
                                    </span>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="text-uppercase small text-muted">Customer</h6>
                                        <p class="mb-1">
                                            <strong><?php echo htmlspecialchars($customer_name ?: "Unnamed customer"); ?></strong>
                                        </p>
                                        <p class="mb-1 small">
                                            <i class="fa-solid fa-phone me-1"></i>
                                            <?php echo htmlspecialchars($customer_phone ?: "No phone provided"); ?>
                                        </p>
                                        <?php if ($customer_messenger): ?>
                                            <p class="mb-1 small">
                                                <i class="fa-brands fa-facebook-messenger me-1"></i>
                                                <?php echo htmlspecialchars($customer_messenger); ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if ($customer_email): ?>
                                            <p class="mb-0 small">
                                                <i class="fa-solid fa-envelope me-1"></i>
                                                <?php echo htmlspecialchars($customer_email); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-uppercase small text-muted">Fulfillment</h6>
                                        <?php if ($fulfillment_mode === "delivery"): ?>
                                            <p class="mb-1 small">
                                                <strong>Subdivision:</strong>
                                                <?php echo htmlspecialchars($delivery_subdivision ?: "Not specified"); ?>
                                            </p>
                                            <p class="mb-1 small">
                                                <strong>Address:</strong>
                                                <?php echo nl2br(htmlspecialchars($delivery_address ?: "Not specified")); ?>
                                            </p>
                                            <p class="mb-1 small">
                                                <strong>Landmark:</strong>
                                                <?php echo htmlspecialchars($delivery_landmark ?: "Not specified"); ?>
                                            </p>
                                        <?php else: ?>
                                            <p class="mb-1 small">
                                                <strong>Pickup at:</strong>
                                                Shawarma Depot pickup point
                                            </p>
                                            <p class="mb-0 small text-muted">
                                                Same location shown on the Menu / Checkout pages.
                                            </p>
                                        <?php endif; ?>

                                        <p class="mt-2 mb-0 small">
                                            <strong>Payment:</strong>
                                            <?php echo htmlspecialchars(strtoupper($payment_method ?: "COD")); ?>
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <h6 class="text-uppercase small text-muted mb-2">Items</h6>
                                <div class="table-responsive mb-3">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Item</th>
                                                <th class="text-nowrap">Details</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Unit</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php if (!empty($cart_items)): ?>
                                            <?php foreach ($cart_items as $item): ?>
                                                <?php
                                                    $name    = htmlspecialchars($item["name"]    ?? "Item");
                                                    $summary = htmlspecialchars($item["summary"] ?? "");
                                                    $qty     = (int)($item["qty"] ?? 0);
                                                    $unit    = (float)($item["unitPrice"] ?? 0);
                                                    $line    = $qty * $unit;
                                                ?>
                                                <tr>
                                                    <td><?php echo $name; ?></td>
                                                    <td class="small text-muted"><?php echo $summary; ?></td>
                                                    <td class="text-end"><?php echo $qty; ?></td>
                                                    <td class="text-end">₱<?php echo number_format($unit, 2); ?></td>
                                                    <td class="text-end">₱<?php echo number_format($line, 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-muted text-center small">
                                                    No items found in this order.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="w-100" style="max-width: 320px;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted small">Subtotal</span>
                                            <span class="small">₱<?php echo number_format($subtotal, 2); ?></span>
                                        </div>
                                        <?php if ($fulfillment_mode === "delivery"): ?>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Delivery fee</span>
                                                <span class="small">₱<?php echo number_format($delivery_fee, 2); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total</span>
                                            <span class="text-success">
                                                ₱<?php echo number_format($total_amount, 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($order_notes): ?>
                                    <hr>
                                    <h6 class="text-uppercase small text-muted mb-1">Notes to Shawarma Depot</h6>
                                    <p class="small mb-0">
                                        <?php echo nl2br(htmlspecialchars($order_notes)); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php else: ?>
                <!-- Error state -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle bg-danger-subtle text-danger d-inline-flex align-items-center justify-content-center me-3"
                                         style="width: 44px; height: 44px;">
                                        <i class="fa-solid fa-circle-exclamation fa-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="h5 mb-1">We couldn't confirm your order</h2>
                                        <p class="small text-muted mb-0">
                                            <?php echo htmlspecialchars($error_msg ?: "No additional error info."); ?>
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <p class="small text-muted mb-3">
                                    If you reached this page by refreshing or opening it directly, please return to the
                                    menu and place your order again.
                                </p>

                                <div class="d-flex flex-wrap gap-2">
                                    <a href="menu.php" class="btn btn-warning btn-sm text-dark fw-semibold">
                                        <i class="fa-solid fa-utensils me-1"></i>
                                        Back to Menu
                                    </a>
                                    <a href="contact.php" class="btn btn-outline-dark btn-sm">
                                        <i class="fa-solid fa-headset me-1"></i>
                                        Contact Support
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<!-- BACK TO TOP (same as about.php) -->
<button type="button"
        class="btn btn-warning text-dark fw-bold back-to-top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- FOOTER -->
<footer class="footer mt-0 py-3 bg-dark text-light">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small>&copy; <?php echo date('Y'); ?> Shawarma Depot. All rights reserved.</small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="about.php" class="me-3 small text-light text-decoration-none">About</a>
                <a href="contact.php" class="me-3 small text-light text-decoration-none">Contact</a>
                <a href="admin/login.php" class="small text-light text-decoration-none">
                    <i class="fa-solid fa-user-gear me-1"></i> Admin
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script type="module" src="assets/js/main.js"></script>

<?php if ($insert_ok): ?>
<script>
    // Clear cart + order details after success
    try {
        localStorage.removeItem("shawarma_cart");
        localStorage.removeItem("shawarma_order_details");
    } catch (e) {}
</script>
<?php endif; ?>

</body>
</html>
