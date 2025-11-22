<?php
// track-order.php - Track Order page (frontend + backend)

// ---------------------- DB connection ----------------------
$host = "localhost";
$user = "root";
$pass = "Pokemon2003";      // your password
$db   = "shawarma_depot";  // your DB name

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// ---------------------- Read query params (from GET) ----------------------
$orderCodeInput  = trim($_GET['order_code'] ?? $_GET['code'] ?? ''); // support ?code= from order-confirmed
$orderPhoneInput = trim($_GET['order_phone'] ?? '');

// Normalize phone to digits only for matching
$phoneDigits = preg_replace('/\D+/', '', $orderPhoneInput);

// Flags / containers for template
$hasSearched = ($orderCodeInput !== '' || $orderPhoneInput !== '');
$order       = null;
$cartItems   = [];
$errorMsg    = "";

// ---------------------- If GET search submitted, look up order ----------------------
if ($hasSearched) {
    if ($orderCodeInput === '' || $phoneDigits === '') {
        $errorMsg = "Please enter both order ID and mobile number.";
    } else {
        // Match order_code exactly, phone ignoring spaces/dashes/+ etc
        $sql = "
            SELECT *
            FROM orders
            WHERE order_code = ?
              AND REPLACE(REPLACE(REPLACE(customer_phone, ' ', ''), '-', ''), '+', '') = ?
            LIMIT 1
        ";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ss", $orderCodeInput, $phoneDigits);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                if ($result && $result->num_rows === 1) {
                    $order = $result->fetch_assoc();

                    // Decode cart_json
                    if (!empty($order['cart_json'])) {
                        $decoded = json_decode($order['cart_json'], true);
                        if (is_array($decoded)) {
                            $cartItems = $decoded;
                        }
                    }
                }
            }
            $stmt->close();
        }
        if (!$order && $errorMsg === "") {
            $errorMsg = "We couldn't find an order matching that ID and mobile number.";
        }
    }
}

// ---------------------- Cancellation logic (backend) ----------------------

// Fake OTP for cancellation
const CANCEL_OTP = "000000";

$cancelError   = "";
$cancelSuccess = "";

// We submit cancellation with POST, but keep the search context via GET (?order_code=&order_phone=)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_action'])) {
    $enteredOtp       = trim($_POST['otp_code'] ?? "");
    $orderToCancel    = trim($_POST['cancel_order_code'] ?? "");
    $phoneDigitsCancel = preg_replace('/\D+/', '', trim($_POST['cancel_phone'] ?? ""));

    if ($enteredOtp !== CANCEL_OTP) {
        $cancelError = "Incorrect code. For testing, use 000000.";
    } else {
        // Fetch order to cancel
        $stmt = $mysqli->prepare("
            SELECT * FROM orders
            WHERE order_code = ?
              AND REPLACE(REPLACE(REPLACE(customer_phone,' ',''),'-',''),'+','') = ?
            LIMIT 1
        ");
        if ($stmt) {
            $stmt->bind_param("ss", $orderToCancel, $phoneDigitsCancel);
            $stmt->execute();
            $result = $stmt->get_result();
            $found  = $result->fetch_assoc();
            $stmt->close();
        }

        if (empty($found)) {
            $cancelError = "No matching order found for cancellation.";
        } else {
            if (!in_array($found['status'], ['pending', 'confirmed'], true)) {
                $cancelError = "This order can no longer be cancelled.";
            } else {
                // Update DB status
                $stmt2 = $mysqli->prepare("UPDATE orders SET status='cancelled' WHERE id=?");
                if ($stmt2) {
                    $oid = (int)$found['id'];
                    $stmt2->bind_param("i", $oid);
                    $stmt2->execute();
                    $stmt2->close();

                    $cancelSuccess = "Your order has been successfully cancelled.";

                    // If the currently viewed order matches, update it in memory too
                    if ($order && $order['order_code'] === $found['order_code']) {
                        $order['status'] = 'cancelled';
                    }
                } else {
                    $cancelError = "We couldn't update your order at this time.";
                }
            }
        }
    }
}

// ---------------------- Helper: Status display ----------------------
function humanStatusLabel(string $status): string {
    switch ($status) {
        case 'pending':          return 'Pending';
        case 'confirmed':        return 'Confirmed';
        case 'preparing':        return 'Preparing';
        case 'out_for_delivery': return 'Out for delivery';
        case 'completed':        return 'Completed';
        case 'cancelled':        return 'Cancelled';
        default:                 return ucfirst($status);
    }
}

function statusBadgeClass(string $status): string {
    switch ($status) {
        case 'completed':
            return 'bg-success-subtle text-success border border-success-subtle';
        case 'out_for_delivery':
            return 'bg-info-subtle text-info border border-info-subtle';
        case 'preparing':
            return 'bg-warning-subtle text-warning border border-warning-subtle';
        case 'confirmed':
            return 'bg-primary-subtle text-primary border border-primary-subtle';
        case 'cancelled':
            return 'bg-danger-subtle text-danger border border-danger-subtle';
        case 'pending':
        default:
            return 'bg-secondary-subtle text-secondary border border-secondary-subtle';
    }
}

// Map statuses to a simple 4-step flow
$statusOrder = [
    'pending'          => 0,
    'confirmed'        => 1,
    'preparing'        => 2,
    'out_for_delivery' => 3,
    'completed'        => 4, // treat as end of flow
    'cancelled'        => -1 // special
];

$currentStepIndex = -1;
$status = $order['status'] ?? '';
if (isset($statusOrder[$status])) {
    $currentStepIndex = $statusOrder[$status];
}

// Rough progress % for the bar
$progressPercent = 0;
if ($currentStepIndex >= 0) {
    if ($currentStepIndex <= 0)      $progressPercent = 15;
    elseif ($currentStepIndex == 1)  $progressPercent = 35;
    elseif ($currentStepIndex == 2)  $progressPercent = 65;
    elseif ($currentStepIndex >= 3)  $progressPercent = 90;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Track Order | Shawarma Depot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex flex-column min-vh-100"><!-- full-height page -->

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

<main class="py-4 flex-grow-1"><!-- stretches to push footer down -->
    <section class="py-4 section-info">
        <div class="container">

            <!-- Heading -->
            <div class="mb-4 text-center text-md-start">
                <div class="section-title mb-1 text-uppercase">
                    Track Order
                </div>
                <h1 class="section-heading h3 mb-2">
                    Check the status of your shawarma
                </h1>
                <p class="text-muted mb-0">
                    Enter your order ID and mobile number to see the latest status.
                </p>
            </div>

            <div class="row g-4">
                <!-- Search / form column -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-magnifying-glass me-2 text-warning"></i>
                                Find your order
                            </h5>
                            <p class="small text-muted mb-3">
                                Your order ID can be found in the confirmation page or message we sent.
                                Use the same mobile number you used when ordering.
                            </p>

                            <form method="get" action="track-order.php">
                                <div class="mb-3">
                                    <label for="orderCode" class="form-label small text-uppercase text-muted">
                                        Order ID
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="orderCode"
                                           name="order_code"
                                           placeholder="e.g. DY37JLS867"
                                           value="<?php echo htmlspecialchars($orderCodeInput); ?>"
                                           required>
                                </div>

                                <div class="mb-3">
                                    <label for="orderPhone" class="form-label small text-uppercase text-muted">
                                        Mobile Number
                                    </label>
                                    <input type="tel"
                                           class="form-control"
                                           id="orderPhone"
                                           name="order_phone"
                                           placeholder="09XX XXX XXXX"
                                           value="<?php echo htmlspecialchars($orderPhoneInput); ?>"
                                           required>
                                </div>

                                <?php if ($hasSearched && $errorMsg): ?>
                                    <div class="alert alert-danger py-2 small mb-3">
                                        <i class="fa-solid fa-circle-exclamation me-1"></i>
                                        <?php echo htmlspecialchars($errorMsg); ?>
                                    </div>
                                <?php endif; ?>

                                <div class="d-grid d-sm-flex align-items-center justify-content-between mt-3">
                                    <button type="submit" class="btn btn-warning fw-semibold text-dark mb-2 mb-sm-0">
                                        <i class="fa-solid fa-location-crosshairs me-1"></i>
                                        Track Order
                                    </button>
                                    <small class="text-muted ms-sm-2">
                                        Having trouble? Message us on our Facebook page.
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert alert-light border small mt-3 mb-0">
                        <i class="fa-solid fa-shield-halved me-1 text-warning"></i>
                        For your safety, only orders with matching order ID and phone number
                        will be shown here.
                    </div>
                </div>

                <!-- Status / details column -->
                <div class="col-lg-7">
                    <?php if ($order): ?>
                        <!-- Live result from DB -->
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">
                                            Order ID
                                        </div>
                                        <div class="fw-bold font-monospace">
                                            <?php echo htmlspecialchars($order['order_code']); ?>
                                        </div>
                                    </div>
                                    <div class="text-end mt-2 mt-sm-0">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Current status
                                        </div>
                                        <span class="badge <?php echo statusBadgeClass($status); ?>">
                                            <?php if ($status === 'out_for_delivery'): ?>
                                                <i class="fa-solid fa-motorcycle me-1"></i>
                                            <?php elseif ($status === 'preparing'): ?>
                                                <i class="fa-solid fa-fire-burner me-1"></i>
                                            <?php elseif ($status === 'completed'): ?>
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                            <?php elseif ($status === 'cancelled'): ?>
                                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                            <?php else: ?>
                                                <i class="fa-solid fa-receipt me-1"></i>
                                            <?php endif; ?>
                                            <?php echo htmlspecialchars(humanStatusLabel($status)); ?>
                                        </span>
                                    </div>
                                </div>

                                <?php if (!empty($order['created_at'])): ?>
                                    <div class="small text-muted mb-2">
                                        Placed at:
                                        <strong><?php echo htmlspecialchars($order['created_at']); ?></strong>
                                    </div>
                                <?php endif; ?>

                                <hr>

                                <?php if ($status !== 'cancelled'): ?>
                                    <!-- Stepper style status -->
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between text-center">
                                            <?php
                                            $steps = [
                                                ['icon' => 'fa-receipt',     'label' => 'Order placed', 'minIndex' => 0],
                                                ['icon' => 'fa-check',       'label' => 'Confirmed',    'minIndex' => 1],
                                                ['icon' => 'fa-fire-burner', 'label' => 'Preparing',    'minIndex' => 2],
                                                ['icon' => 'fa-motorcycle',  'label' => 'Out for delivery / Completed', 'minIndex' => 3],
                                            ];
                                            foreach ($steps as $idx => $step):
                                                $active = ($currentStepIndex >= $step['minIndex']);
                                                $badgeClass = $active ? 'bg-warning text-dark' : 'bg-secondary text-light';
                                            ?>
                                                <div class="flex-fill">
                                                    <div class="badge rounded-pill <?php echo $badgeClass; ?>">
                                                        <i class="fa-solid <?php echo $step['icon']; ?>"></i>
                                                    </div>
                                                    <div class="small mt-1 fw-semibold">
                                                        <?php echo htmlspecialchars($step['label']); ?>
                                                    </div>
                                                    <div class="small text-muted">
                                                        <?php echo $active ? 'Done' : 'Pending'; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <div class="progress mt-3" style="height: 6px;">
                                            <div class="progress-bar bg-warning" style="width: <?php echo $progressPercent; ?>%;"></div>
                                        </div>
                                    </div>

                                    <!-- Cancellation area (only if pending / confirmed) -->
                                    <?php if (in_array($status, ['pending', 'confirmed'], true)): ?>
                                        <hr>
                                        <h6 class="text-uppercase small text-muted mb-2">Cancel this order</h6>

                                        <?php if ($cancelError): ?>
                                            <div class="alert alert-danger small py-2 mb-2">
                                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                                <?php echo htmlspecialchars($cancelError); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ($cancelSuccess): ?>
                                            <div class="alert alert-success small py-2 mb-2">
                                                <i class="fa-solid fa-circle-check me-1"></i>
                                                <?php echo htmlspecialchars($cancelSuccess); ?>
                                            </div>
                                        <?php endif; ?>

                                        <?php if (!$cancelSuccess): ?>
                                            <form method="post"
                                                  class="small"
                                                  action="track-order.php?order_code=<?php echo urlencode($orderCodeInput); ?>&order_phone=<?php echo urlencode($orderPhoneInput); ?>">
                                                <input type="hidden" name="cancel_action" value="1">
                                                <input type="hidden" name="cancel_order_code" value="<?php echo htmlspecialchars($order['order_code']); ?>">
                                                <input type="hidden" name="cancel_phone" value="<?php echo htmlspecialchars($orderPhoneInput); ?>">

                                                <div class="mb-2">
                                                    <label class="form-label small text-uppercase text-muted">
                                                        Confirmation Code
                                                    </label>
                                                    <input type="text"
                                                           name="otp_code"
                                                           maxlength="6"
                                                           class="form-control form-control-sm"
                                                           placeholder="______"
                                                           inputmode="numeric"
                                                           pattern="[0-9]*">
                                                    <div class="form-text">
                                                        For testing, use <strong>000000</strong>.
                                                    </div>
                                                </div>

                                                <button type="submit"
                                                        class="btn btn-outline-danger btn-sm fw-semibold">
                                                    <i class="fa-solid fa-ban me-1"></i>
                                                    Cancel my order
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                <?php else: ?>
                                    <!-- Cancelled state -->
                                    <div class="alert alert-danger small mb-3">
                                        <i class="fa-solid fa-circle-xmark me-1"></i>
                                        This order has been <strong>cancelled</strong>.<br>
                                        If this was a mistake, please place a new order.
                                    </div>
                                <?php endif; ?>

                                <!-- Order basic info -->
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Name
                                        </div>
                                        <div class="fw-semibold">
                                            <?php echo htmlspecialchars($order['customer_name']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Mobile
                                        </div>
                                        <div class="fw-semibold">
                                            <?php echo htmlspecialchars($order['customer_phone']); ?>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Fulfillment
                                        </div>
                                        <div class="fw-semibold">
                                            <?php
                                            $modeLabel = ($order['fulfillment_mode'] === 'delivery')
                                                ? 'Delivery'
                                                : 'Pickup';
                                            $extra = '';
                                            if ($order['fulfillment_mode'] === 'delivery' && !empty($order['delivery_subdivision'])) {
                                                $extra = ' – ' . $order['delivery_subdivision'];
                                            }
                                            echo htmlspecialchars($modeLabel . $extra);
                                            ?>
                                        </div>
                                    </div>
                                    <?php if ($order['fulfillment_mode'] === 'delivery'): ?>
                                        <div class="col-sm-6">
                                            <div class="small text-uppercase text-muted mb-1">
                                                Address
                                            </div>
                                            <div class="fw-semibold small">
                                                <?php
                                                $addr = trim(($order['delivery_address'] ?? '') . ' ' . ($order['delivery_landmark'] ?? ''));
                                                echo nl2br(htmlspecialchars($addr ?: 'Not specified'));
                                                ?>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-sm-6">
                                            <div class="small text-uppercase text-muted mb-1">
                                                Pickup point
                                            </div>
                                            <div class="fw-semibold small">
                                                Shawarma Depot pickup location
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <hr>

                                <!-- Order items summary (from cart_json) -->
                                <h6 class="mb-2">
                                    <i class="fa-solid fa-bowl-food me-2 text-warning"></i>
                                    Items in this order
                                </h6>

                                <?php if (!empty($cartItems)): ?>
                                    <ul class="list-unstyled small mb-3">
                                        <?php foreach ($cartItems as $item): ?>
                                            <?php
                                            $iname   = htmlspecialchars($item['name'] ?? 'Item');
                                            $summary = htmlspecialchars($item['summary'] ?? '');
                                            $qty     = (int)($item['qty'] ?? 0);
                                            ?>
                                            <li class="mb-1">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <?php echo $iname; ?>
                                                        <?php if ($summary): ?>
                                                            <span class="text-muted"> – <?php echo $summary; ?></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <span class="fw-semibold">x<?php echo $qty; ?></span>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p class="small text-muted mb-3">
                                        No item details available for this order.
                                    </p>
                                <?php endif; ?>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small text-muted">
                                        Total amount
                                    </div>
                                    <div class="fw-bold fs-5 text-success">
                                        ₱<?php echo number_format((float)$order['total_amount'], 2); ?>
                                    </div>
                                </div>

                                <div class="alert alert-light border small mt-3 mb-0">
                                    <i class="fa-solid fa-circle-info me-1 text-warning"></i>
                                    As the admin updates the order status in the dashboard,
                                    this page will automatically reflect the latest progress.
                                </div>
                            </div>
                        </div>

                    <?php elseif (!$hasSearched): ?>

                        <!-- Empty state / instructions -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center text-md-start">
                                <h5 class="card-title mb-2">
                                    <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>
                                    Waiting for a tracking request
                                </h5>
                                <p class="text-muted mb-3">
                                    After placing an order, you’ll see your order ID on the confirmation page
                                    and in our messages. Type it on the left together with your mobile number
                                    to see updates here.
                                </p>
                                <ul class="text-muted small mb-0">
                                    <li>Order just placed? It may take a short moment to appear.</li>
                                    <li>Make sure your order ID is typed exactly as shown.</li>
                                    <li>If you can’t find your order, message our Facebook page.</li>
                                </ul>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- Searched but no result (errorMsg already shown above) -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center text-md-start">
                                <h5 class="card-title mb-2">
                                    <i class="fa-solid fa-circle-question me-2 text-warning"></i>
                                    No matching order found
                                </h5>
                                <p class="text-muted mb-2">
                                    We couldn't find an order with that combination of order ID and mobile number.
                                </p>
                                <ul class="text-muted small mb-0">
                                    <li>Double-check if the order ID has no extra spaces or typos.</li>
                                    <li>Use the exact same mobile number you gave when ordering.</li>
                                    <li>If this keeps happening, send us a message so we can assist you.</li>
                                </ul>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- Back-to-top button -->
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

<!-- Bootstrap JS + main.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script type="module" src="assets/js/main.js"></script>
</body>
</html>
