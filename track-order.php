<?php
// track-order.php - Track Order page (frontend, backend-ready shell)

// In the future, you'll fetch actual order data from the database using these:
$orderCode = isset($_GET['order_code']) ? trim($_GET['order_code']) : '';
$placeholderHasResult = !empty($orderCode); // For now, just pretend something was found if code is filled
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
<body>

<!-- ---------------------- Navbar ---------------------- -->

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            Shawarma <span>Depot</span>
        </a>
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainNavbar"
                aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="track-order.php">
                        Track Order
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.php">About</a>
                </li>
                <li class="nav-item me-lg-2">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-warning btn-sm text-dark fw-semibold" href="menu.php">
                        <i class="fa-solid fa-bag-shopping me-1"></i>
                        Order Now
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <!-- ---------------------- Track order section ---------------------- -->
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
                                Your order ID can be found in the confirmation message we sent
                                (SMS or Messenger). Use the same mobile number you used when ordering.
                            </p>

                            <!-- 
                                Backend note:
                                Later, this form will query your orders table using order_code and phone.
                             -->
                            <form method="get" action="track-order.php">
                                <div class="mb-3">
                                    <label for="orderCode" class="form-label small text-uppercase text-muted">
                                        Order ID
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="orderCode"
                                           name="order_code"
                                           placeholder="e.g. SD-2025-00123"
                                           value="<?php echo htmlspecialchars($orderCode); ?>"
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
                                           required>
                                </div>

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
                        will be shown here once verification is fully enabled.
                    </div>
                </div>

                <!-- Status / details column -->
                <div class="col-lg-7">
                    <?php if ($placeholderHasResult): ?>
                        <!-- ---------------------- Example result (placeholder) ---------------------- -->
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body">
                                <div class="d-flex flex-wrap align-items-center justify-content-between mb-2">
                                    <div>
                                        <div class="small text-uppercase text-muted mb-1">
                                            Order ID
                                        </div>
                                        <div class="fw-bold">
                                            <?php echo htmlspecialchars($orderCode); ?>
                                            <!-- Example: SD-2025-00123 -->
                                        </div>
                                    </div>
                                    <div class="text-end mt-2 mt-sm-0">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Current status
                                        </div>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fa-solid fa-motorcycle me-1"></i>
                                            Out for delivery
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <!-- Stepper style status using plain Bootstrap -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between text-center">
                                        <div class="flex-fill">
                                            <div class="badge rounded-pill bg-warning text-dark">
                                                <i class="fa-solid fa-receipt"></i>
                                            </div>
                                            <div class="small mt-1 fw-semibold">
                                                Order placed
                                            </div>
                                            <div class="small text-muted">
                                                Confirmed
                                            </div>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="badge rounded-pill bg-warning text-dark">
                                                <i class="fa-solid fa-check"></i>
                                            </div>
                                            <div class="small mt-1 fw-semibold">
                                                Verified
                                            </div>
                                            <div class="small text-muted">
                                                Phone confirmed
                                            </div>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="badge rounded-pill bg-warning text-dark">
                                                <i class="fa-solid fa-fire-burner"></i>
                                            </div>
                                            <div class="small mt-1 fw-semibold">
                                                Preparing
                                            </div>
                                            <div class="small text-muted">
                                                In the kitchen
                                            </div>
                                        </div>
                                        <div class="flex-fill">
                                            <div class="badge rounded-pill bg-success text-dark">
                                                <i class="fa-solid fa-motorcycle"></i>
                                            </div>
                                            <div class="small mt-1 fw-semibold">
                                                Out for delivery
                                            </div>
                                            <div class="small text-muted">
                                                Rider on the way
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Simple progress bar under the steps -->
                                    <div class="progress mt-3" style="height: 6px;">
                                        <div class="progress-bar bg-warning" style="width: 85%;"></div>
                                    </div>
                                </div>

                                <!-- Order basic info -->
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Name
                                        </div>
                                        <div class="fw-semibold">
                                            Juan Dela Cruz
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Mobile
                                        </div>
                                        <div class="fw-semibold">
                                            09XX XXX XXXX
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Fulfillment
                                        </div>
                                        <div class="fw-semibold">
                                            Delivery – San Marino Classic
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="small text-uppercase text-muted mb-1">
                                            Placed at
                                        </div>
                                        <div class="fw-semibold">
                                            Today • 6:32 PM
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <!-- Order items summary (static example for now) -->
                                <h6 class="mb-2">
                                    <i class="fa-solid fa-bowl-food me-2 text-warning"></i>
                                    Items in this order
                                </h6>

                                <ul class="list-unstyled small mb-3">
                                    <li class="d-flex justify-content-between">
                                        <span>Shawarma Wrap – LARGE Solo (Beef, Spicy)</span>
                                        <span class="fw-semibold">x1</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span>Shawarma Rice – Overload (Chicken)</span>
                                        <span class="fw-semibold">x1</span>
                                    </li>
                                    <li class="d-flex justify-content-between">
                                        <span>Plum Tea – Large</span>
                                        <span class="fw-semibold">x2</span>
                                    </li>
                                </ul>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="small text-muted">
                                        Estimated total
                                    </div>
                                    <div class="fw-bold fs-5 text-success">
                                        ₱390
                                    </div>
                                </div>

                                <div class="alert alert-light border small mt-3 mb-0">
                                    <i class="fa-solid fa-circle-info me-1 text-warning"></i>
                                    Once backend is connected, this section will show live data
                                    from your orders, including updated status from the admin panel.
                                </div>
                            </div>
                        </div>

                    <?php else: ?>

                        <!-- ---------------------- Empty state / instructions ---------------------- -->
                        <div class="card shadow-sm border-0">
                            <div class="card-body text-center text-md-start">
                                <h5 class="card-title mb-2">
                                    <i class="fa-solid fa-clock-rotate-left me-2 text-warning"></i>
                                    Waiting for a tracking request
                                </h5>
                                <p class="text-muted mb-3">
                                    After placing an order, you’ll receive an order ID via SMS or Messenger.
                                    Type it on the left together with your mobile number to see updates here.
                                </p>
                                <ul class="text-muted small mb-0">
                                    <li>Order just placed? It may take a short moment to appear.</li>
                                    <li>Make sure your order ID is typed exactly as shown.</li>
                                    <li>If you can’t find your order, message our Facebook page.</li>
                                </ul>
                            </div>
                        </div>

                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- ---------------------- Back-to-top button ---------------------- -->

<button type="button"
        class="btn btn-warning text-dark fw-bold back-to-top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- ---------------------- Footer ---------------------- -->

<footer class="footer mt-5">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="d-block">
                    &copy; <?php echo date('Y'); ?> Shawarma Depot. All rights reserved.
                </small>
                <small class="text-muted">
                    Fresh shawarma, friendly service, and overload toppings.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="me-2">Follow us:</small>
                <a href="#" class="me-2"><i class="fa-brands fa-facebook"></i></a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS + main.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
