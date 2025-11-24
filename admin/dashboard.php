<?php
// admin/dashboard.php - Admin Overview

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

// ---------------------- Aggregate stats ----------------------

// Total orders
$total_orders = 0;
$res = $mysqli->query("SELECT COUNT(*) AS c FROM orders");
if ($res && $row = $res->fetch_assoc()) {
    $total_orders = (int)$row['c'];
}

// Count by status
$status_counts = [
    'pending'          => 0,
    'confirmed'        => 0,
    'preparing'        => 0,
    'out_for_delivery' => 0,
    'completed'        => 0,
    'cancelled'        => 0,
];

$res = $mysqli->query("SELECT status, COUNT(*) AS c FROM orders GROUP BY status");
if ($res) {
    while ($row = $res->fetch_assoc()) {
        $st = $row['status'];
        if (isset($status_counts[$st])) {
            $status_counts[$st] = (int)$row['c'];
        }
    }
}

// Today's orders
$today_orders = 0;
$res = $mysqli->query("SELECT COUNT(*) AS c FROM orders WHERE DATE(created_at) = CURDATE()");
if ($res && $row = $res->fetch_assoc()) {
    $today_orders = (int)$row['c'];
}

// Latest 5 orders
$latest_orders = [];
$stmt = $mysqli->prepare("
    SELECT id, order_code, customer_name, total_amount, status, created_at
    FROM orders
    ORDER BY created_at DESC
    LIMIT 5
");
if ($stmt && $stmt->execute()) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $latest_orders[] = $row;
    }
    $stmt->close();
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin | Dashboard | Shawarma Depot</title>
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
<body class="bg-light d-flex flex-column min-vh-100"><!-- full-height admin page -->

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
                    <a class="nav-link active" href="dashboard.php">
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

<main class="py-4 flex-grow-1"><!-- flex-grow keeps footer at bottom -->
    <section class="py-3">
        <div class="container">

            <!-- Admin indicator -->
            <div class="alert alert-dark d-flex align-items-center small mb-4">
                <i class="fa-solid fa-user-gear me-2"></i>
                <div>
                    <strong>Admin Area &middot; Dashboard</strong>
                    <span class="text-muted">
                        Quick overview of what’s happening with customer orders.
                    </span>
                </div>
            </div>

            <!-- Heading -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div class="mb-2">
                    <div class="section-title mb-1 text-uppercase">
                        Admin &middot; Overview
                    </div>
                    <h1 class="section-heading h4 mb-0">
                        Today at Shawarma Depot
                    </h1>
                    <small class="text-muted">
                        See pending orders and recent activity at a glance.
                    </small>
                </div>

                <div class="mb-2">
                    <a href="orders.php" class="btn btn-sm btn-dark">
                        <i class="fa-solid fa-receipt me-1"></i>
                        Go to Orders
                    </a>
                </div>
            </div>

            <!-- Top stats cards -->
             <div id="js-dashboard-live">
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-uppercase text-muted">Total Orders</span>
                                    <i class="fa-solid fa-layer-group text-muted"></i>
                                </div>
                                <h3 class="mb-0">
                                    <?php echo $total_orders; ?>
                                </h3>
                                <small class="text-muted">All-time in the system</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-uppercase text-muted">Pending</span>
                                    <i class="fa-solid fa-hourglass-half text-muted"></i>
                                </div>
                                <h3 class="mb-0">
                                    <?php echo $status_counts['pending']; ?>
                                </h3>
                                <small class="text-muted">Waiting for confirmation</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-uppercase text-muted">Preparing</span>
                                    <i class="fa-solid fa-fire-burner text-muted"></i>
                                </div>
                                <h3 class="mb-0">
                                    <?php echo $status_counts['preparing']; ?>
                                </h3>
                                <small class="text-muted">Currently being cooked</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="small text-uppercase text-muted">Today</span>
                                    <i class="fa-solid fa-calendar-day text-muted"></i>
                                </div>
                                <h3 class="mb-0">
                                    <?php echo $today_orders; ?>
                                </h3>
                                <small class="text-muted">Orders placed today</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status breakdown + latest orders -->
                <div class="row g-3">
                    <!-- Status breakdown -->
                    <div class="col-lg-5">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-3">
                                    <i class="fa-solid fa-chart-pie me-2 text-warning"></i>
                                    Orders by Status
                                </h5>

                                <?php if ($total_orders === 0): ?>
                                    <p class="text-muted small mb-0">
                                        No orders yet. Once customers start ordering, you'll see numbers here.
                                    </p>
                                <?php else: ?>
                                    <ul class="list-group list-group-flush">
                                        <?php foreach ($STATUS_LABELS as $key => $label): ?>
                                            <?php
                                                $count = $status_counts[$key] ?? 0;
                                                $badgeClass = $STATUS_BADGE_CLASS[$key] ?? 'bg-secondary';
                                            ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center small">
                                                <span>
                                                    <span class="badge <?php echo $badgeClass; ?> me-2">
                                                        &nbsp;
                                                    </span>
                                                    <?php echo htmlspecialchars($label); ?>
                                                </span>
                                                <span class="fw-semibold">
                                                    <?php echo $count; ?>
                                                </span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Latest orders -->
                    <div class="col-lg-7">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <h5 class="card-title mb-3 d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>
                                        Latest Orders
                                    </span>
                                    <a href="orders.php" class="small text-decoration-none">
                                        View all
                                        <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </a>
                                </h5>

                                <?php if (empty($latest_orders)): ?>
                                    <p class="text-muted small mb-0">
                                        No orders yet. Once orders come in, the latest five will show here.
                                    </p>
                                <?php else: ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Customer</th>
                                                    <th class="text-end">Total</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Placed</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach ($latest_orders as $o): ?>
                                                <?php
                                                    $st      = $o['status'] ?? 'pending';
                                                    $stLabel = $STATUS_LABELS[$st] ?? ucfirst($st);
                                                    $stClass = $STATUS_BADGE_CLASS[$st] ?? 'bg-secondary';
                                                ?>
                                                <tr>
                                                    <td class="small">
                                                        <span class="badge bg-dark text-warning me-1">
                                                            <span class="font-monospace">
                                                                <?php echo htmlspecialchars($o['order_code']); ?>
                                                            </span>
                                                        </span>
                                                        <span class="text-muted small">
                                                            #<?php echo (int)$o['id']; ?>
                                                        </span>
                                                    </td>
                                                    <td class="small">
                                                        <?php echo htmlspecialchars($o['customer_name']); ?>
                                                    </td>
                                                    <td class="small text-end">
                                                        ₱<?php echo number_format((float)$o['total_amount'], 2); ?>
                                                    </td>
                                                    <td class="small">
                                                        <span class="badge <?php echo $stClass; ?>">
                                                            <?php echo htmlspecialchars($stLabel); ?>
                                                        </span>
                                                    </td>
                                                    <td class="small text-end">
                                                        <?php echo htmlspecialchars($o['created_at']); ?>
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="order-details.php?id=<?php echo (int)$o['id']; ?>"
                                                        class="btn btn-sm btn-outline-dark">
                                                            <i class="fa-solid fa-magnifying-glass me-1"></i>
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
    const liveBox = document.getElementById('js-dashboard-live');
    if (!liveBox) return;

    function refreshDashboard() {
        fetch(window.location.href, { cache: 'no-store' })
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const updated = doc.getElementById('js-dashboard-live');
                if (updated) {
                    liveBox.innerHTML = updated.innerHTML;
                }
            })
            .catch(err => {
                console.error('Dashboard auto-refresh failed:', err);
            });
    }

    // e.g. every 7 seconds
    setInterval(refreshDashboard, 7000);
});
</script>

</body>
</html>
