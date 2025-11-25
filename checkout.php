<?php
// checkout.php - Checkout page (frontend-first, backend-ready)
?>

<?php
$paymongo_cancelled = isset($_GET['paymongo_cancel']);
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
          crossorigin="anonymous">

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="d-flex flex-column min-vh-100">

<?php if (!empty($paymongo_cancelled)): ?>
    <div class="alert alert-warning small">
        Online payment was cancelled or failed. 
        You can try again, or choose <strong>Cash</strong> as your payment method.
    </div>
<?php endif; ?>

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

<main class="py-4 flex-grow-1">
    <section class="py-4">
        <div class="container">
            <!-- Page heading -->
            <div class="mb-4 text-center text-md-start">
                <div class="section-title mb-1 text-uppercase">
                    Checkout
                </div>
                <h1 class="section-heading h3 mb-2">
                    Review your order & confirm
                </h1>
                <p class="text-muted mb-0">
                    Almost done! Check your details and confirm your order.
                </p>
            </div>

            <div class="row g-4">
                <!-- LEFT: Checkout confirmation + OTP (2-step) -->
                <div class="col-lg-7">
                    <div class="delivery-box mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <span class="badge bg-dark text-warning">
                                CHECKOUT CONFIRMATION
                            </span>
                            <small class="text-muted">
                                Your details are based on what you entered on the previous page.
                            </small>
                        </div>

                        <!-- ONE FORM, TWO STEPS (toggled by checkout.js) -->
                        <form id="checkoutForm" action="order-confirmed.php" method="post" autocomplete="off">
                            <!-- Hidden fields populated from localStorage via checkout.js -->
                            <input type="hidden" name="customer_name"        id="hiddenCustomerName">
                            <input type="hidden" name="customer_phone"       id="hiddenCustomerPhone">
                            <input type="hidden" name="customer_messenger"   id="hiddenCustomerMessenger">
                            <input type="hidden" name="customer_email"       id="hiddenCustomerEmail">

                            <input type="hidden" name="fulfillment_mode"     id="hiddenFulfillmentMode">
                            <input type="hidden" name="delivery_subdivision" id="hiddenSubdivision">
                            <input type="hidden" name="delivery_address"     id="hiddenDeliveryAddress">
                            <input type="hidden" name="delivery_landmark"    id="hiddenDeliveryLandmark">

                            <!-- Money -->
                            <input type="hidden" name="subtotal"             id="hiddenSubtotal">
                            <input type="hidden" name="delivery_fee"         id="hiddenDeliveryFee">
                            <input type="hidden" name="total_amount"         id="hiddenTotalAmount">

                            <!-- Cart snapshot -->
                            <input type="hidden" name="cart_json"            id="hiddenCartJson">

                            <!-- ========= STEP 1: SUMMARY & PAYMENT ========= -->
                            <div id="checkoutStep1">
                                <!-- Summary of contact + delivery / pickup info -->
                                <h5 class="mb-3">
                                    <i class="fa-solid fa-user me-2 text-warning"></i>
                                    Your Details
                                </h5>

                                <div id="checkoutDetailsSummary"
                                     class="small text-muted mb-3">
                                    <!-- Filled by checkout.js from localStorage (shawarma_order_details) -->
                                    Loading your details...
                                </div>

                                <hr class="my-3">

                                <!-- Payment method -->
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
                                            Cash
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input"
                                            type="radio"
                                            name="payment_method"
                                            id="paymentPayMongo"
                                            value="paymongo">
                                        <label class="form-check-label" for="paymentPayMongo">
                                            Online Payment (PayMongo – cards & e-wallet)
                                        </label>
                                    </div>
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

                                <!-- Step 1 button -->
                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <small class="text-muted">
                                        By placing your order, you agree to be contacted for confirmation.
                                    </small>
                                    <button type="button"
                                            id="btnConfirmStep1"
                                            class="btn btn-warning fw-semibold text-dark">
                                        Confirm Order
                                        <i class="fa-solid fa-arrow-right-long ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- ========= STEP 2: OTP ========= -->
                            <div id="checkoutStep2" style="display:none;">

                                <h5 class="mb-2">
                                    <i class="fa-solid fa-shield-halved me-2 text-warning"></i>
                                    Enter the 6-digit confirmation code
                                </h5>

                                <p class="text-muted small mb-3">
                                    Normally this would be sent via SMS or Messenger.
                                    For testing, use code <strong>000000</strong>.
                                </p>

                                <div class="mb-3">
                                    <label for="otpCode"
                                           class="form-label small text-uppercase text-muted">
                                        6-digit code
                                    </label>
                                    <input type="text"
                                           class="form-control text-center fs-5"
                                           id="otpCode"
                                           maxlength="6"
                                           placeholder="______"
                                           inputmode="numeric"
                                           pattern="[0-9]*">
                                    <div class="invalid-feedback" id="otpError">
                                        Incorrect code. Please try again.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <button type="button"
                                            id="btnBackStep2"
                                            class="btn btn-outline-secondary btn-sm">
                                        <i class="fa-solid fa-arrow-left me-1"></i>
                                        Back
                                    </button>
                                    <button type="button"
                                            id="btnPlaceOrder"
                                            class="btn btn-warning fw-semibold text-dark d-inline-block">
                                        Place Order
                                        <i class="fa-solid fa-check ms-1"></i>
                                    </button>
                                </div>

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
                                Review what you’re about to order.
                            </p>

                            <!-- Dynamic cart items from localStorage -->
                            <div id="checkoutCartItems" class="mb-3">
                                <div class="text-muted small">
                                    Loading your cart...
                                </div>
                            </div>

                            <hr>

                            <!-- Totals (dynamic) -->
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold" id="checkoutSubtotal">₱0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-1" id="checkoutDeliveryRow">
                                <span class="text-muted">
                                    Delivery fee
                                    <i class="fa-solid fa-circle-question ms-1 text-warning"
                                    title="Based on area you selected earlier."></i>
                                </span>
                                <span class="fw-semibold text-muted" id="checkoutDeliveryFeeLabel">
                                    Calculated by area
                                </span>
                            </div>

                            <div class="d-flex justify-content-between mb-0">
                                <span class="fw-bold">Estimated Total</span>
                                <span class="fw-bold text-success" id="checkoutTotal">₱0</span>
                            </div>
                        </div>
                    </div>
                </div> <!-- /RIGHT -->
            </div>
        </div>
    </section>
</main>

<!-- BACK TO TOP BUTTON (works with main.js if present) -->
<button type="button"
        class="btn btn-warning text-dark fw-bold back-to-top">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<footer class="footer mt-0 py-3 bg-dark text-light mt-auto">
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

<!-- Bootstrap JS + app scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script type="module" src="assets/js/main.js"></script>

</body>
</html>
