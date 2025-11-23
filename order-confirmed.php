<?php
// order-confirmed.php

// DB connection
$host = "localhost";
$user = "root";
$pass = "Pokemon2003";      // your password
$db   = "shawarma_depot";  // your DB name

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
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

// ===============================
// 1) POST REQUEST: create order
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $order_code           = generateOrderCode();
    $customer_name        = trim($_POST["customer_name"]        ?? "");
    $customer_phone       = trim($_POST["customer_phone"]       ?? "");
    $customer_messenger   = trim($_POST["customer_messenger"]   ?? "");
    $customer_email       = trim($_POST["customer_email"]       ?? "");
    $fulfillment_mode     = trim($_POST["fulfillment_mode"]     ?? "");
    $delivery_subdivision = trim($_POST["delivery_subdivision"] ?? "");
    $delivery_address     = trim($_POST["delivery_address"]     ?? "");
    $delivery_landmark    = trim($_POST["delivery_landmark"]    ?? "");
    $payment_method       = trim($_POST["payment_method"]       ?? "");
    $order_notes          = trim($_POST["order_notes"]          ?? "");

    $subtotal      = isset($_POST["subtotal"])     ? (float)$_POST["subtotal"]     : 0;
    $delivery_fee  = isset($_POST["delivery_fee"]) ? (float)$_POST["delivery_fee"] : 0;
    $total_amount  = isset($_POST["total_amount"]) ? (float)$_POST["total_amount"] : 0;

    $cart_json = $_POST["cart_json"] ?? "[]";
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
    }

// ===============================
// 2) GET REQUEST: show existing order
// ===============================
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
