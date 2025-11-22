<?php
// admin/order-details.php - Single Order View for Admin

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

// ---------------------- DB connection ----------------------
$host = "localhost";
$user = "root";
$pass = "Pokemon2003";
$db   = "shawarma_depot";

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// ---------------------- Status helpers ----------------------
$STATUS_LABELS = [
    'pending'          => 'Pending',
    'confirmed'        => 'Confirmed',
    'preparing'        => 'Preparing',
    'out_for_delivery' => 'Out for Delivery',
    'completed'        => 'Completed',
    'cancelled'        => 'Cancelled',
];

$STATUS_BADGE_CLASS = [
    'pending'          => 'bg-secondary',
    'confirmed'        => 'bg-info text-dark',
    'preparing'        => 'bg-warning text-dark',
    'out_for_delivery' => 'bg-primary',
    'completed'        => 'bg-success',
    'cancelled'        => 'bg-danger',
];

// Status flow (same as orders.php)
$STATUS_NEXT = [
    'pending'          => 'confirmed',
    'confirmed'        => 'preparing',
    'preparing'        => 'out_for_delivery',
    'out_for_delivery' => 'completed',
    'completed'        => null,
    'cancelled'        => null,
];

$flash_message = "";
$flash_type    = "success";

// ---------------------- Identify which order to show ----------------------
$order_id  = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$order_code_param = trim($_GET['code'] ?? '');

if ($order_id <= 0 && $order_code_param === '') {
    $flash_message = "No order specified.";
    $flash_type    = "danger";
}

// ---------------------- Handle POST actions (advance / cancel) ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $posted_order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

    if ($posted_order_id > 0) {
        // Get current status
        $stmt = $mysqli->prepare("SELECT status FROM orders WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $posted_order_id);
            if ($stmt->execute()) {
                $res = $stmt->get_result();
                $row = $res->fetch_assoc();
            }
            $stmt->close();
        }

        if (!empty($row)) {
            $current_status = $row['status'] ?? 'pending';

            if ($_POST['action'] === 'advance_status') {
                $next_status = $STATUS_NEXT[$current_status] ?? null;

                if ($next_status === null) {
                    $flash_message = "Order #{$posted_order_id} is already " . ($STATUS_LABELS[$current_status] ?? $current_status) . " and cannot be advanced further.";
                    $flash_type    = "warning";
                } else {
                    $stmt2 = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ?");
                    if ($stmt2) {
                        $stmt2->bind_param("si", $next_status, $posted_order_id);
                        if ($stmt2->execute()) {
                            $flash_message = "Order #{$posted_order_id} status updated to " . $STATUS_LABELS[$next_status] . ".";
                            $flash_type    = "success";
                        } else {
                            $flash_message = "Failed to update status: " . $stmt2->error;
                            $flash_type    = "danger";
                        }
                        $stmt2->close();
                    } else {
                        $flash_message = "Failed to prepare update statement.";
                        $flash_type    = "danger";
                    }
                }
            } elseif ($_POST['action'] === 'cancel_order') {
                // Admin instant cancel
                if ($current_status === 'completed') {
                    $flash_message = "Completed orders cannot be cancelled.";
                    $flash_type    = "warning";
                } elseif ($current_status === 'cancelled') {
                    $flash_message = "This order is already cancelled.";
                    $flash_type    = "info";
                } else {
                    $stmt2 = $mysqli->prepare("UPDATE orders SET status = 'cancelled' WHERE id = ?");
                    if ($stmt2) {
                        $stmt2->bind_param("i", $posted_order_id);
                        if ($stmt2->execute()) {
                            $flash_message = "Order #{$posted_order_id} has been cancelled.";
                            $flash_type    = "success";
                        } else {
                            $flash_message = "Failed to cancel order: " . $stmt2->error;
                            $flash_type    = "danger";
                        }
                        $stmt2->close();
                    } else {
                        $flash_message = "Failed to prepare cancellation statement.";
                        $flash_type    = "danger";
                    }
                }
            }
        } else {
            $flash_message = "Order not found.";
            $flash_type    = "danger";
        }
    } else {
        $flash_message = "Invalid order.";
        $flash_type    = "danger";
    }

    // After POST, make sure we still show this order
    if ($order_id <= 0) {
        $order_id = $posted_order_id;
    }
}

// ---------------------- Fetch the order now (after any updates) ----------------------
$order       = null;
$order_items = [];
$cart_items  = [];

if ($order_id > 0) {
    $stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $order_id);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            $order = $res->fetch_assoc();
        }
        $stmt->close();
    }
} elseif ($order_code_param !== '') {
    $stmt = $mysqli->prepare("SELECT * FROM orders WHERE order_code = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("s", $order_code_param);
        if ($stmt->execute()) {
            $res = $stmt->get_result();
            $order = $res->fetch_assoc();
        }
        $stmt->close();
    }
}

// If we found an order, update $order_id (in case it came from code)
if ($order && isset($order['id'])) {
    $order_id = (int)$order['id'];
}

// Fetch order_items
if ($order_id > 0 && $order) {
    $stmt_items = $mysqli->prepare("
        SELECT
            product_name,
            summary,
            unit_price,
            quantity,
            line_total
        FROM order_items
        WHERE order_id = ?
        ORDER BY id
    ");
    if ($stmt_items) {
        $stmt_items->bind_param("i", $order_id);
        if ($stmt_items->execute()) {
            $res_items = $stmt_items->get_result();
            while ($row = $res_items->fetch_assoc()) {
                $order_items[] = $row;
            }
        }
        $stmt_items->close();
    }

    // Fallback: if no order_items but cart_json exists, decode it so we at least show something
    if (empty($order_items) && !empty($order['cart_json'])) {
        $decoded = json_decode($order['cart_json'], true);
        if (is_array($decoded)) {
            $cart_items = $decoded;
        }
    }
}

// For progress stepper (similar logic as track-order)
$status = $order['status'] ?? '';
$statusOrder = [
    'pending'          => 0,
    'confirmed'        => 1,
    'preparing'        => 2,
    'out_for_delivery' => 3,
    'completed'        => 4,
    'cancelled'        => -1,
];
$currentStepIndex = isset($statusOrder[$status]) ? $statusOrder[$status] : -1;

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
    <title>Admin | Order Details | Shawarma Depot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light">

<!-- ADMIN NAVBAR (same look as orders.php) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="dashboard.php">
            <img src="../assets/images/logo.png" alt="Shawarma Depot Logo" width="40" height="40">
            <div class="ms-2 d-flex flex-column lh-1">
                <strong>Shawarma Depot | Admin Panel</strong>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        <i class="fa-solid fa-chart-line me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orders.php">
                        <i class="fa-solid fa-receipt me-1"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">
                        <i class="fa-solid fa-burger me-1"></i> Menu
                    </a>
                </li>
                <li class="nav-item ms-lg-3">
                    <a class="btn btn-outline-light btn-sm" href="../index.php" target="_blank">
                        <i class="fa-solid fa-globe me-1"></i> View Site
                    </a>
                </li>
                <li class="nav-item ms-lg-2">
                    <a class="btn btn-warning btn-sm text-dark fw-semibold" href="logout.php">
                        <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <section class="py-3">
        <div class="container">

            <!-- Admin indicator -->
            <div class="alert alert-dark d-flex align-items-center small mb-4">
                <i class="fa-solid fa-user-gear me-2"></i>
                <div>
                    <strong>Admin Area &middot; Order Details</strong>
                    <span class="text-muted">
                        You’re viewing a single customer order. Status changes and cancellations here are visible on the Track Order page.
                    </span>
                </div>
            </div>

            <!-- Page heading / back link -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2">
                    <div class="section-title mb-1 text-uppercase">
                        Admin &middot; Order Details
                    </div>
                    <h1 class="section-heading h4 mb-0">
                        Order
                        <?php if ($order): ?>
                            <span class="font-monospace">
                                <?php echo htmlspecialchars($order['order_code']); ?>
                            </span>
                        <?php else: ?>
                            Not Found
                        <?php endif; ?>
                    </h1>
                    <?php if ($order): ?>
                        <small class="text-muted">
                            Internal ID: #<?php echo (int)$order['id']; ?>
                        </small>
                    <?php endif; ?>
                </div>

                <div class="mb-2">
                    <a href="orders.php" class="btn btn-outline-dark btn-sm">
                        <i class="fa-solid fa-arrow-left me-1"></i>
                        Back to Orders
                    </a>
                </div>
            </div>

            <!-- Flash message -->
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flash_type); ?> alert-dismissible fade show small" role="alert">
                    <?php echo htmlspecialchars($flash_message); ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!$order): ?>
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center py-5">
                        <p class="mb-2">
                            <i class="fa-solid fa-circle-exclamation fa-2x text-danger"></i>
                        </p>
                        <p class="fw-semibold mb-1">Order not found.</p>
                        <p class="text-muted small mb-3">
                            It may have been deleted or the link is invalid.
                        </p>
                        <a href="orders.php" class="btn btn-sm btn-dark">
                            <i class="fa-solid fa-arrow-left me-1"></i>
                            Back to Orders
                        </a>
                    </div>
                </div>
            <?php else: ?>

                <?php
                    $status       = $order['status'] ?? 'pending';
                    $badgeClass   = $STATUS_BADGE_CLASS[$status] ?? 'bg-secondary';
                    $statusText   = $STATUS_LABELS[$status] ?? ucfirst($status);
                    $mode         = $order['fulfillment_mode'] ?? '';
                    $modeLabel    = $mode === 'delivery' ? 'Delivery' : ($mode === 'pickup' ? 'Pickup' : 'N/A');
                    $created_at   = $order['created_at'] ?? '';
                    $nextStatus   = $STATUS_NEXT[$status] ?? null;
                ?>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <!-- Top summary row -->
                        <div class="d-flex flex-wrap justify-content-between align-items-start mb-3">
                            <div class="mb-2">
                                <div class="small text-uppercase text-muted mb-1">
                                    Order Code
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-dark text-warning me-2">
                                        <span class="font-monospace">
                                            <?php echo htmlspecialchars($order['order_code']); ?>
                                        </span>
                                    </span>
                                    <span class="small text-muted">
                                        Internal ID: #<?php echo (int)$order['id']; ?>
                                    </span>
                                </div>
                                <div class="small text-muted mt-1">
                                    Placed at <?php echo htmlspecialchars($created_at); ?>
                                </div>
                            </div>

                            <div class="text-end mb-2">
                                <div class="small text-uppercase text-muted mb-1">
                                    Current Status
                                </div>
                                <span class="badge <?php echo $badgeClass; ?> mb-1">
                                    <?php echo htmlspecialchars($statusText); ?>
                                </span>
                                <div class="small text-muted">
                                    Mode:
                                    <strong><?php echo htmlspecialchars($modeLabel); ?></strong>
                                </div>
                                <div class="small text-muted">
                                    Total:
                                    <strong>₱<?php echo number_format((float)$order['total_amount'], 2); ?></strong>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Stepper / progress (like Track Order) -->
                        <?php if ($status !== 'cancelled'): ?>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between text-center">
                                    <?php
                                    $steps = [
                                        ['icon' => 'fa-receipt',     'label' => 'Order placed', 'minIndex' => 0],
                                        ['icon' => 'fa-check',       'label' => 'Confirmed',    'minIndex' => 1],
                                        ['icon' => 'fa-fire-burner', 'label' => 'Preparing',    'minIndex' => 2],
                                        ['icon' => 'fa-motorcycle',  'label' => 'Out for delivery / Completed', 'minIndex' => 3],
                                    ];
                                    foreach ($steps as $step):
                                        $active = ($currentStepIndex >= $step['minIndex']);
                                        $badgeStepClass = $active ? 'bg-warning text-dark' : 'bg-secondary text-light';
                                    ?>
                                        <div class="flex-fill">
                                            <div class="badge rounded-pill <?php echo $badgeStepClass; ?>">
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
                        <?php else: ?>
                            <div class="alert alert-danger small mb-3">
                                <i class="fa-solid fa-circle-xmark me-1"></i>
                                This order has been <strong>cancelled</strong>.
                            </div>
                        <?php endif; ?>

                        <!-- Actions: next status + cancel -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                            <div class="small text-muted mb-2">
                                <?php if ($nextStatus): ?>
                                    Next step:
                                    <strong><?php echo htmlspecialchars($STATUS_LABELS[$nextStatus]); ?></strong>
                                <?php else: ?>
                                    No further automatic steps available for this status.
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mb-2">
                                <?php if ($nextStatus): ?>
                                    <form method="post" class="d-inline">
                                        <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                        <input type="hidden" name="action" value="advance_status">
                                        <button type="submit"
                                                class="btn btn-sm btn-dark fw-semibold">
                                            <i class="fa-solid fa-forward-step me-1"></i>
                                            Move to <?php echo htmlspecialchars($STATUS_LABELS[$nextStatus]); ?>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php if ($status !== 'cancelled' && $status !== 'completed'): ?>
                                    <form method="post" class="d-inline"
                                          onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                        <input type="hidden" name="action" value="cancel_order">
                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger fw-semibold">
                                            <i class="fa-solid fa-ban me-1"></i>
                                            Cancel Order
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>

                        <hr>

                        <!-- Two-column layout: Customer / fulfillment + Items -->
                        <div class="row">
                            <!-- Customer + fulfillment -->
                            <div class="col-md-4 mb-3">
                                <h6 class="text-uppercase small text-muted mb-2">
                                    Customer
                                </h6>
                                <p class="mb-1 small">
                                    <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                                </p>
                                <p class="mb-1 small">
                                    <i class="fa-solid fa-phone me-1"></i>
                                    <?php echo htmlspecialchars($order['customer_phone']); ?>
                                </p>
                                <?php if (!empty($order['customer_messenger'])): ?>
                                    <p class="mb-1 small">
                                        <i class="fa-brands fa-facebook-messenger me-1"></i>
                                        <?php echo htmlspecialchars($order['customer_messenger']); ?>
                                    </p>
                                <?php endif; ?>
                                <?php if (!empty($order['customer_email'])): ?>
                                    <p class="mb-1 small">
                                        <i class="fa-solid fa-envelope me-1"></i>
                                        <?php echo htmlspecialchars($order['customer_email']); ?>
                                    </p>
                                <?php endif; ?>

                                <hr class="my-2">

                                <h6 class="text-uppercase small text-muted mb-2">
                                    Fulfillment
                                </h6>
                                <?php if ($mode === 'delivery'): ?>
                                    <p class="mb-1 small">
                                        <strong>Subdivision:</strong>
                                        <?php echo htmlspecialchars($order['delivery_subdivision'] ?: 'Not specified'); ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <strong>Address:</strong><br>
                                        <?php echo nl2br(htmlspecialchars($order['delivery_address'] ?: 'Not specified')); ?>
                                    </p>
                                    <p class="mb-1 small">
                                        <strong>Landmark:</strong>
                                        <?php echo htmlspecialchars($order['delivery_landmark'] ?: 'Not specified'); ?>
                                    </p>
                                <?php else: ?>
                                    <p class="mb-1 small">
                                        <strong>Pickup at:</strong><br>
                                        Shawarma Depot pickup point
                                    </p>
                                <?php endif; ?>

                                <p class="mt-2 mb-0 small">
                                    <strong>Payment:</strong>
                                    <?php echo htmlspecialchars(strtoupper($order['payment_method'] ?: 'COD')); ?>
                                </p>
                            </div>

                            <!-- Items + money -->
                            <div class="col-md-8 mb-3">
                                <h6 class="text-uppercase small text-muted mb-2">
                                    Items
                                </h6>

                                <div class="table-responsive mb-2">
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
                                        <?php if (!empty($order_items)): ?>
                                            <?php foreach ($order_items as $it): ?>
                                                <tr>
                                                    <td class="small">
                                                        <?php echo htmlspecialchars($it['product_name']); ?>
                                                    </td>
                                                    <td class="small text-muted">
                                                        <?php echo htmlspecialchars($it['summary']); ?>
                                                    </td>
                                                    <td class="text-end small">
                                                        <?php echo (int)$it['quantity']; ?>
                                                    </td>
                                                    <td class="text-end small">
                                                        ₱<?php echo number_format((float)$it['unit_price'], 2); ?>
                                                    </td>
                                                    <td class="text-end small">
                                                        ₱<?php echo number_format((float)$it['line_total'], 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php elseif (!empty($cart_items)): ?>
                                            <?php foreach ($cart_items as $ci): ?>
                                                <?php
                                                $cname   = htmlspecialchars($ci['name'] ?? 'Item');
                                                $csummary= htmlspecialchars($ci['summary'] ?? '');
                                                $cqty    = (int)($ci['qty'] ?? 0);
                                                $cunit   = (float)($ci['unitPrice'] ?? 0);
                                                $cline   = $cqty * $cunit;
                                                ?>
                                                <tr>
                                                    <td class="small"><?php echo $cname; ?></td>
                                                    <td class="small text-muted"><?php echo $csummary; ?></td>
                                                    <td class="text-end small"><?php echo $cqty; ?></td>
                                                    <td class="text-end small">
                                                        ₱<?php echo number_format($cunit, 2); ?>
                                                    </td>
                                                    <td class="text-end small">
                                                        ₱<?php echo number_format($cline, 2); ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="5" class="text-center small text-muted">
                                                    No line items found for this order.
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div class="w-100" style="max-width: 280px;">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted small">Subtotal</span>
                                            <span class="small">
                                                ₱<?php echo number_format((float)$order['subtotal'], 2); ?>
                                            </span>
                                        </div>
                                        <?php if ($mode === 'delivery'): ?>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="text-muted small">Delivery fee</span>
                                                <span class="small">
                                                    ₱<?php echo number_format((float)$order['delivery_fee'], 2); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span class="small">Total</span>
                                            <span class="small text-success">
                                                ₱<?php echo number_format((float)$order['total_amount'], 2); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <?php if (!empty($order['order_notes'])): ?>
                                    <hr class="my-2">
                                    <h6 class="text-uppercase small text-muted mb-1">
                                        Customer Notes
                                    </h6>
                                    <p class="small mb-0">
                                        <?php echo nl2br(htmlspecialchars($order['order_notes'])); ?>
                                    </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endif; ?>
        </div>
    </section>
</main>

<!-- BACK TO TOP -->
<button type="button"
        class="btn btn-warning text-dark fw-bold back-to-top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- ADMIN FOOTER -->
<footer class="footer mt-4 bg-dark text-light py-3">
    <div class="container">
        <div class="row gy-2 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="d-block">
                    &copy; <?php echo date('Y'); ?> Shawarma Depot &middot; Admin Panel
                </small>
                <small class="d-block">
                    For authorized staff only. Changes are visible to customers in real time.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="../index.php" class="small text-decoration-none text-light">
                    <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>
                    Go to customer site
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script type="module" src="../assets/js/main.js"></script>
</body>
</html>
