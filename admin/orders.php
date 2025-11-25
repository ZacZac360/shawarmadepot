<?php
// admin/orders.php - Admin Orders Management

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
    'out_for_delivery' => 'Out for Delivery / Ready for Pickup',
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

// Flow: what’s the “next” status for each
$STATUS_NEXT = [
    'pending'          => 'confirmed',
    'confirmed'        => 'preparing',
    'preparing'        => 'out_for_delivery',
    'out_for_delivery' => 'completed',
    'completed'        => null,
    'cancelled'        => null,
];

$flash_message = $_SESSION['flash_message'] ?? "";
$flash_type    = $_SESSION['flash_type'] ?? "success";
unset($_SESSION['flash_message'], $_SESSION['flash_type']);

// ---------------------- Handle status advance (POST) ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'advance_status') {
    $order_id = isset($_POST['order_id']) ? (int)$_POST['order_id'] : 0;

    if ($order_id > 0) {
        // Get current status from DB
        $stmt = $mysqli->prepare("SELECT status FROM orders WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("i", $order_id);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $row    = $result->fetch_assoc();
            }
            $stmt->close();
        }

        if (!empty($row)) {
            $current_status = $row['status'] ?? 'pending';
            $next_status    = $STATUS_NEXT[$current_status] ?? null;

            if ($next_status === null) {
                $_SESSION['flash_message'] = "Order #{$order_id} is already {$STATUS_LABELS[$current_status]} and cannot be advanced further.";
                $_SESSION['flash_type']    = "warning";
            } else {
                $stmt = $mysqli->prepare("UPDATE orders SET status = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $next_status, $order_id);
                    if ($stmt->execute()) {
                        $_SESSION['flash_message'] = "Order #{$order_id} status updated to " . $STATUS_LABELS[$next_status] . ".";
                        $_SESSION['flash_type']    = "success";
                    } else {
                        $_SESSION['flash_message'] = "Failed to update status: " . $stmt->error;
                        $_SESSION['flash_type']    = "danger";
                    }
                    $stmt->close();
                } else {
                    $_SESSION['flash_message'] = "Failed to prepare update statement.";
                    $_SESSION['flash_type']    = "danger";
                }
            }
        } else {
            $_SESSION['flash_message'] = "Order not found.";
            $_SESSION['flash_type']    = "danger";
        }
    } else {
        $_SESSION['flash_message'] = "Invalid order.";
        $_SESSION['flash_type']    = "danger";
    }

    // 🔁 Redirect to break the POST → refresh loop
    // optionally preserve current filter:
    $redirectStatus = $_GET['status'] ?? 'all';
    header("Location: orders.php?status=" . urlencode($redirectStatus));
    exit;
}


// ---------------------- Filter by status (GET) ----------------------
$current_filter = $_GET['status'] ?? 'all';
if ($current_filter !== 'all' && !isset($STATUS_LABELS[$current_filter])) {
    $current_filter = 'all';
}

// ---------------------- Fetch orders ----------------------
$orders = [];

if ($current_filter === 'all') {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
} else {
    $sql = "SELECT * FROM orders WHERE status = ? ORDER BY created_at DESC";
    $stmt = $mysqli->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $current_filter);
    }
}

if ($stmt && $stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    $stmt->close();
}

// ---------------------- Fetch order items for all orders ----------------------
$order_items_map = []; // order_id => [items]

if (!empty($orders)) {
    $order_ids = array_column($orders, 'id');
    $in_clause = implode(',', array_fill(0, count($order_ids), '?'));

    $sql_items = "
        SELECT
            order_id,
            product_name,
            summary,
            unit_price,
            quantity,
            line_total
        FROM order_items
        WHERE order_id IN ($in_clause)
        ORDER BY order_id, id
    ";

    $stmt_items = $mysqli->prepare($sql_items);
    if ($stmt_items) {
        $types = str_repeat('i', count($order_ids));
        $stmt_items->bind_param($types, ...$order_ids);

        if ($stmt_items->execute()) {
            $res_items = $stmt_items->get_result();
            while ($row = $res_items->fetch_assoc()) {
                $oid = (int)$row['order_id'];
                if (!isset($order_items_map[$oid])) {
                    $order_items_map[$oid] = [];
                }
                $order_items_map[$oid][] = $row;
            }
        }
        $stmt_items->close();
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Orders | Shawarma Depot</title>
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

<!-- ADMIN NAVBAR -->
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
                    <a class="nav-link active" href="orders.php">
                        <i class="fa-solid fa-receipt me-1"></i> Orders
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
            <!-- Admin indicator / banner -->
            <div class="alert alert-dark d-flex align-items-center small mb-4">
                <i class="fa-solid fa-user-gear me-2"></i>
                <div>
                    <strong>Admin Area:</strong> You’re managing live customer orders.
                    <span class="text-muted">Status changes and cancellations here are reflected on the customer Track Order page.</span>
                </div>
            </div>

            <!-- Page heading -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2">
                    <div class="section-title mb-1 text-uppercase">
                        Admin &middot; Orders
                    </div>
                    <h1 class="section-heading h4 mb-0">
                        All Customer Orders
                    </h1>
                    <small class="text-muted">
                        Advance orders step-by-step as they move from queue to completion.
                    </small>
                </div>

                <!-- Status filter pills -->
                <div class="mb-2">
                    <div class="btn-group btn-group-sm" role="group" aria-label="Filter by status">
                        <?php
                        $filters = ['all' => 'All'] + $STATUS_LABELS;
                        foreach ($filters as $key => $label):
                            $active = ($current_filter === $key) ? 'active' : '';
                        ?>
                            <a href="?status=<?php echo urlencode($key); ?>"
                               class="btn btn-outline-dark <?php echo $active; ?>">
                                <?php echo htmlspecialchars($label); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Flash message -->
            <?php if ($flash_message): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flash_type); ?> alert-dismissible fade show small" role="alert">
                    <?php echo htmlspecialchars($flash_message); ?>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
            <div id="js-orders-live">
                <?php if (empty($orders)): ?>
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center py-5">
                            <p class="mb-1">
                                <i class="fa-solid fa-clipboard-list fa-2x text-muted mb-2"></i>
                            </p>
                            <p class="mb-1 fw-semibold">No orders found.</p>
                            <p class="text-muted small mb-0">
                                Once customers start placing orders, they will appear here.
                            </p>
                        </div>
                    </div>
                <?php else: ?>

                <!-- Orders list -->
                <div class="row g-3">
                    <?php foreach ($orders as $order): ?>
                        <?php
                            $oid        = (int)$order['id'];
                            $status     = $order['status'] ?? 'pending';
                            $badgeClass = $STATUS_BADGE_CLASS[$status] ?? 'bg-secondary';
                            $statusText = $STATUS_LABELS[$status] ?? ucfirst($status);
                            $items      = $order_items_map[$oid] ?? [];

                            $created_at = $order['created_at'] ?? '';
                            $mode       = $order['fulfillment_mode'] ?? '';
                            $modeLabel  = $mode === 'delivery' ? 'Delivery' : ($mode === 'pickup' ? 'Pickup' : 'N/A');
                            $nextStatus = $STATUS_NEXT[$status] ?? null;
                        ?>
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                                        <div class="mb-2">
                                            <div class="small text-muted text-uppercase">
                                                Order Code
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="badge bg-dark text-warning me-2">
                                                    <span class="font-monospace">
                                                        <?php echo htmlspecialchars($order['order_code']); ?>
                                                    </span>
                                                </span>
                                                <span class="small text-muted">
                                                    #<?php echo $oid; ?>
                                                </span>
                                            </div>
                                            <div class="small text-muted mt-1">
                                                Placed at
                                                <?php echo htmlspecialchars($created_at); ?>
                                            </div>
                                        </div>

                                        <div class="text-end mb-2">
                                            <a href="order-details.php?id=<?php echo $oid; ?>"
                                                class="btn btn-sm btn-outline-dark">
                                                    <i class="fa-solid fa-magnifying-glass me-1"></i>
                                                    View details
                                                </a>

                                            <span class="badge <?php echo $badgeClass; ?> mb-1">
                                                <?php echo htmlspecialchars($statusText); ?>
                                            </span>
                                            <div class="small text-muted">
                                                Mode: <strong><?php echo htmlspecialchars($modeLabel); ?></strong>
                                            </div>
                                            <div class="small text-muted">
                                                Total: <strong>₱<?php echo number_format((float)$order['total_amount'], 2); ?></strong>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-3">

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
                                                    <?php if (!empty($items)): ?>
                                                        <?php foreach ($items as $it): ?>
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

                                    <hr class="my-3">

                                    <!-- Status advance control -->
                                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                                        <div class="small text-muted mb-2">
                                            <?php if ($nextStatus): ?>
                                                Next step:
                                                <strong><?php echo htmlspecialchars($STATUS_LABELS[$nextStatus]); ?></strong>
                                            <?php else: ?>
                                                This order is <strong><?php echo htmlspecialchars($statusText); ?></strong>.
                                                No further steps.
                                            <?php endif; ?>
                                        </div>

                                        <?php if ($nextStatus): ?>
                                            <form method="post" class="mb-2">
                                                <input type="hidden" name="action" value="advance_status">
                                                <input type="hidden" name="order_id" value="<?php echo $oid; ?>">
                                                <button type="submit"
                                                        class="btn btn-sm btn-dark fw-semibold">
                                                    <i class="fa-solid fa-forward-step me-1"></i>
                                                    Move to <?php echo htmlspecialchars($STATUS_LABELS[$nextStatus]); ?>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('js-orders-live');
    if (!container) return;

    // Keep current filter (?status=…) in the URL
    const baseUrl = new URL(window.location.href);

    function refreshOrders() {
        fetch(baseUrl.toString(), { cache: 'no-store' })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const updated = doc.getElementById('js-orders-live');
                if (updated) {
                    // Replace inner content with the new HTML
                    container.innerHTML = updated.innerHTML;
                }
            })
            .catch(err => {
                console.error('Orders auto-refresh failed:', err);
            });
    }

    // e.g. every 5 seconds
    setInterval(refreshOrders, 5000);
});
</script>

</body>
</html>
