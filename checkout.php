<?php
// checkout.php - Checkout page (frontend-first, backend-ready)
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Checkout | Shawarma Depot</title>
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

<!-- NAVBAR -->
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
                    <a class="nav-link" href="track-order.php">Track Order</a>
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
                        Back to Menu
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    <section class="py-4">
        <div class="container">
            <!-- Page heading -->
            <div class="mb-4 text-center text-md-start">
                <div class="section-title mb-1 text-uppercase">
                    Checkout
                </div>
                <h1 class="section-heading h3 mb-2">
                    Review your order & enter your details
                </h1>
                <p class="text-muted mb-0">
                    Almost done! Fill out your info below and confirm your order.
                </p>
            </div>

            <div class="row g-4">
                <!-- LEFT: Customer & delivery details -->
                <div class="col-lg-7">
                    <div class="delivery-box mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-dark text-warning">
                                CHECKOUT CONFIRMATION
                            </span>
                            <small class="text-muted">
                                We use this to contact you and deliver your order.
                            </small>
                        </div>

                        <!-- 
                            BACKEND NOTE:
                            Wrap everything in one <form> that the backend will process.
                            You can later change `action` to your order processing script.
                         -->
                        <form action="" method="post">
                            <!-- Contact info -->
                            <h5 class="mb-3">
                                <i class="fa-solid fa-user me-2 text-warning"></i>
                                Contact Information
                            </h5>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="customerName" class="form-label small text-uppercase text-muted">
                                        Full Name
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="customerName"
                                           name="customer_name"
                                           placeholder="Juan Dela Cruz"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label for="customerPhone" class="form-label small text-uppercase text-muted">
                                        Mobile Number
                                    </label>
                                    <input type="tel"
                                           class="form-control"
                                           id="customerPhone"
                                           name="customer_phone"
                                           placeholder="09XX XXX XXXX"
                                           required>
                                </div>
                            </div>

                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label for="customerMessenger" class="form-label small text-uppercase text-muted">
                                        Messenger Name / Link <span class="text-muted">(optional)</span>
                                    </label>
                                    <input type="text"
                                           class="form-control"
                                           id="customerMessenger"
                                           name="customer_messenger"
                                           placeholder="FB name or profile link">
                                </div>
                                <div class="col-md-6">
                                    <label for="customerEmail" class="form-label small text-uppercase text-muted">
                                        Email <span class="text-muted">(optional)</span>
                                    </label>
                                    <input type="email"
                                           class="form-control"
                                           id="customerEmail"
                                           name="customer_email"
                                           placeholder="you@example.com">
                                </div>
                            </div>

                            <!-- Fulfillment choice -->
                            <h5 class="mb-3">
                                <i class="fa-solid fa-motorcycle me-2 text-warning"></i>
                                How would you like to get your order?
                            </h5>

                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="fulfillment_mode"
                                           id="fulfillmentDelivery"
                                           value="delivery"
                                           required>
                                    <label class="form-check-label" for="fulfillmentDelivery">
                                        Delivery
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="fulfillment_mode"
                                           id="fulfillmentPickup"
                                           value="pickup">
                                    <label class="form-check-label" for="fulfillmentPickup">
                                        Pickup
                                    </label>
                                </div>
                            </div>

                            <div class="small text-muted mb-3" id="cartFulfillmentNote">
                                Choose Delivery or Pickup above.
                            </div>

                            <!-- Delivery details (shown when Delivery is selected) -->
                            <div id="deliveryDetailsFields" style="display:none;">
                                <hr class="my-3">

                                <h6 class="small text-uppercase text-muted mb-2">
                                    Delivery Details
                                </h6>

                                <div class="mb-3">
                                    <label for="subdivisionSelect"
                                           class="form-label small text-uppercase text-muted">
                                        Subdivision / Area
                                    </label>
                                    <select class="form-select"
                                            id="subdivisionSelect"
                                            name="delivery_subdivision">
                                        <option value="">Select your area...</option>
                                        <optgroup label="San Marino">
                                            <option value="classic">Classic</option>
                                            <option value="heights">Heights</option>
                                            <option value="central">Central</option>
                                            <option value="phase1">Phase 1</option>
                                            <option value="phase2">Phase 2</option>
                                            <option value="phase3">Phase 3</option>
                                            <option value="phase4">Phase 4</option>
                                            <option value="phase5">Phase 5</option>
                                            <option value="north1">North 1</option>
                                            <option value="north2">North 2</option>
                                            <option value="south1">South 1</option>
                                            <option value="south2">South 2</option>
                                        </optgroup>
                                        <optgroup label="Outside San Marino">
                                            <option value="ndgv">North DG Village</option>
                                            <option value="sdgv">South DG Village</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div id="deliveryFeeMessage"
                                     class="alert alert-light border small py-2 mb-3">
                                    Select your subdivision to calculate delivery fee.
                                </div>

                                <!-- These fields are toggled by main.js using #addressFields -->
                                <div id="addressFields" style="display:none;">
                                    <div class="mb-3">
                                        <label for="deliveryAddress"
                                               class="form-label small text-uppercase text-muted">
                                            Complete Address
                                        </label>
                                        <textarea class="form-control"
                                                  id="deliveryAddress"
                                                  name="delivery_address"
                                                  rows="2"
                                                  placeholder="Block, lot, street, house color, etc."></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="deliveryLandmark"
                                               class="form-label small text-uppercase text-muted">
                                            Landmark <span class="text-muted">(optional)</span>
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="deliveryLandmark"
                                               name="delivery_landmark"
                                               placeholder="Near sari-sari store, gate, etc.">
                                    </div>
                                </div>
                            </div>

                            <!-- Payment method -->
                            <hr class="my-3">

                            <h5 class="mb-3">
                                <i class="fa-solid fa-wallet me-2 text-warning"></i>
                                Payment
                            </h5>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="paymentCOD"
                                           value="cod"
                                           checked>
                                    <label class="form-check-label" for="paymentCOD">
                                        Cash on Delivery / Pickup
                                    </label>
                                </div>
                                <!-- For future: GCash, online payment, etc. -->
                                <!--
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="payment_method"
                                           id="paymentGCash"
                                           value="gcash">
                                    <label class="form-check-label" for="paymentGCash">
                                        GCash
                                    </label>
                                </div>
                                -->
                            </div>

                            <!-- Order notes -->
                            <div class="mb-3">
                                <label for="orderNotes"
                                       class="form-label small text-uppercase text-muted">
                                    Notes to Shawarma Depot <span class="text-muted">(optional)</span>
                                </label>
                                <textarea class="form-control"
                                          id="orderNotes"
                                          name="order_notes"
                                          rows="3"
                                          placeholder="Example: less rice, extra spicy, call when outside gate, etc."></textarea>
                            </div>

                            <!-- Place order button (Step 2) -->
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <small class="text-muted">
                                    By placing your order, you agree to be contacted for confirmation.
                                </small>
                                <a href="track-order.php" class="btn btn-warning fw-semibold text-dark">
                                    <i class="fa-solid fa-arrow-right-long ms-1"></i> Proceed to Checkout
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- RIGHT: Order summary -->
                <div class="col-lg-5">
                    <div class="card shadow-sm border-0 mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">
                                    <i class="fa-solid fa-receipt me-2 text-warning"></i>
                                    Order Summary
                                </h5>
                                <a href="menu.php" class="small text-decoration-none">
                                    Edit order
                                </a>
                            </div>
                            <p class="text-muted small mb-3">
                                <!-- BACKEND NOTE:
                                     In the future, populate this list from your session/cart. -->
                                Review what you’re about to order. This will be confirmed by our staff.
                            </p>

                            <!-- Example items (frontend preview). Replace with PHP loop later. -->
                            <div class="mb-3">
                                <div class="cart-item mb-2">
                                    <div class="item-header">
                                        <span>Shawarma Wrap – LARGE Solo</span>
                                        <span class="item-price">₱85</span>
                                    </div>
                                    <div class="item-summary">
                                        Beef • Spicy • + Cheese Sauce
                                    </div>
                                    <div class="qty-box">
                                        <span class="small text-muted me-1">Qty:</span>
                                        <span class="qty-number">1</span>
                                    </div>
                                </div>

                                <div class="cart-item mb-2">
                                    <div class="item-header">
                                        <span>Shawarma Rice – Overload</span>
                                        <span class="item-price">₱150</span>
                                    </div>
                                    <div class="item-summary">
                                        Chicken • Not spicy
                                    </div>
                                    <div class="qty-box">
                                        <span class="small text-muted me-1">Qty:</span>
                                        <span class="qty-number">1</span>
                                    </div>
                                </div>

                                <div class="cart-item mb-0">
                                    <div class="item-header">
                                        <span>Plum Tea – Large</span>
                                        <span class="item-price">₱70</span>
                                    </div>
                                    <div class="item-summary">
                                        Drinks
                                    </div>
                                    <div class="qty-box">
                                        <span class="small text-muted me-1">Qty:</span>
                                        <span class="qty-number">2</span>
                                    </div>
                                </div>

                                <!-- BACKEND NOTE:
                                     Replace all of the above with something like:
                                     <?php foreach ($_SESSION['cart'] as $item): ?>
                                         ...
                                     <?php endforeach; ?>
                                -->
                            </div>

                            <hr>

                            <!-- Totals (static for now, backend later) -->
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">₱375</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">
                                    Delivery fee
                                    <i class="fa-solid fa-circle-question ms-1 text-warning"
                                       title="Based on subdivision selection."></i>
                                </span>
                                <span class="fw-semibold text-muted">
                                    Calculated by area
                                </span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Estimated Total</span>
                                <span class="fw-bold text-success">₱390</span>
                            </div>

                            <div class="alert alert-light border small mb-0">
                                <i class="fa-solid fa-circle-info me-1 text-warning"></i>
                                Final total (including exact delivery fee) will be confirmed
                                via message or call before we prepare your order.
                            </div>
                        </div>
                    </div>

                    <!-- Optional: store info / pickup note -->
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="mb-2">
                                <i class="fa-solid fa-store me-2 text-warning"></i>
                                Pickup Location
                            </h6>
                            <p class="small text-muted mb-1">
                                Shawarma Depot – <strong>Your Area Here</strong>
                            </p>
                            <p class="small text-muted mb-0">
                                <!-- Customize this with your real address -->
                                Inside San Marino, near guardhouse / main road. Exact pin and
                                directions will be sent after you place your order.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<!-- BACK TO TOP BUTTON (works with main.js if present) -->
<button type="button"
        class="btn btn-warning text-dark fw-bold back-to-top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- FOOTER -->
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

<!-- Bootstrap JS + your main.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
