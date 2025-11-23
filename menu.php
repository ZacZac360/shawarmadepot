<?php
// menu.php - full menu
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Menu | Shawarma Depot</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS (same as index.php) -->
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

<main class="py-5">
    <div class="container">
        <!-- PAGE HEADER -->
        <header class="mb-4 text-center text-md-start">
            <p class="section-title mb-1">Our Menu</p>
            <h1 class="section-heading h2 mb-2">Shawarma Depot Favorites</h1>
            <p class="text-muted mb-0">
                Real-deal street shawarma, cooked fresh and loaded to the brim.
                Prices are in PHP and may change without prior notice.
            </p>
        </header>

        <!-- STORE NOTICE -->
        <div class="alert alert-warning small d-flex align-items-center mb-4">
            <i class="fa-solid fa-clock me-2"></i>
            <span>Store hours: 4:00 PM – 11:00 PM • Delivery and pickup available within the area.</span>
        </div>

        <!-- ORDER / DELIVERY DETAILS -->
        <div class="delivery-box small d-flex align-items-start mb-4 gap-3 p-3 rounded-3 shadow-sm">
            <i class="fa-solid fa-location-dot me-2 mt-1"></i>

            <div class="flex-grow-1">
                <!-- Title -->
                <div class="fw-semibold mb-2">Order Details</div>

                <!-- Delivery or Pickup -->
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fulfillment"
                            id="fulfillmentDelivery" value="delivery">
                        <label class="form-check-label small" for="fulfillmentDelivery">Delivery</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="fulfillment"
                            id="fulfillmentPickup" value="pickup">
                        <label class="form-check-label small" for="fulfillmentPickup">Pickup</label>
                    </div>
                </div>
                <!-- These fields only show when DELIVERY is selected -->
                    <div id="deliveryDetailsFields" style="display:none;">
                        <!-- Coverage + partner apps (always visible) -->
                        <div id="deliveryIntro" class="small text-muted mb-2">
                            Our delivery service covers nearby areas of San Marino City, Dasmariñas.
                            For areas beyond our coverage, you can still order via:
                        </div>
                        <div class="mb-3 d-flex flex-wrap gap-2">
                            <a href="https://www.foodpanda.ph/restaurant/aduq/shawarma-depot-dasmarinas-cavite"
                            target="_blank"
                            class="btn panda-btn btn-sm fw-semibold">
                                🐼 Foodpanda
                            </a>
                            <a href="https://food.grab.com/ph/en/restaurant/shawarma-depot-dasmarinas-delivery/2-C7LKAVJUAK3TV2?"
                            target="_blank"
                            class="btn grab-btn btn-sm fw-semibold">
                                🛵 GrabFood
                            </a>
                        </div>

                        <!-- Subdivision Selection -->
                        <label class="small fw-semibold mb-1">Select Subdivision:</label>
                        <select id="subdivisionSelect" class="form-select form-select-sm mb-2">
                            <option value="">-- Select your subdivision --</option>

                            <optgroup label="San Marino City">
                                <option value="classic">San Marino Classic (₱15 DF + ₱10 gate)</option>
                                <option value="heights">San Marino Heights (₱15 DF)</option>
                                <option value="central">San Marino Central (₱15 DF)</option>
                                <option value="phase1">San Marino Phase 1 (₱15 DF)</option>
                                <option value="phase2">San Marino Phase 2 (₱15 DF)</option>
                                <option value="phase3">San Marino Phase 3 (₱15 DF)</option>
                                <option value="phase4">San Marino Phase 4 (₱15 DF)</option>
                                <option value="phase5">San Marino Phase 5 (₱15 DF)</option>
                                <option value="north1">San Marino North 1 (₱15 DF)</option>
                                <option value="north2">San Marino North 2 (₱15 DF)</option>
                                <option value="south1">San Marino South 1 (₱15 DF)</option>
                                <option value="south2">San Marino South 2 (₱15 DF)</option>
                            </optgroup>

                            <optgroup label="Outside Areas (Pickup / 25 + gate)">
                                <option value="ndgv">North Dasma Garden Villa (₱25 DF + ₱10 gate)</option>
                                <option value="sdgv">South Dasma Garden Villa (₱25 DF + ₱10 gate)</option>
                            </optgroup>
                        </select>

                        <!-- Address Fields -->
                        <div id="addressFields" style="display:none;">
                            <label class="small fw-semibold mb-1">Block &amp; Lot:</label>
                            <input type="text" id="addrBlkLot" class="form-control form-control-sm mb-2"
                                placeholder="e.g. Block 3 Lot 4">

                            <label class="small fw-semibold mb-1">Landmark:</label>
                            <input type="text" id="addrLandmark" class="form-control form-control-sm mb-2"
                                placeholder="e.g. Red gate near Phase 2 park">

                            <label class="small fw-semibold mb-1">Customer Name:</label>
                            <input type="text" id="addrName" class="form-control form-control-sm mb-2"
                                placeholder="Your full name">

                            <label class="small fw-semibold mb-1">Contact Number:</label>
                            <input type="text" id="addrPhone" class="form-control form-control-sm mb-2"
                                placeholder="09XXXXXXXXX">
                        </div>

                        <!-- Fee message (changes based on subdivision) -->
                        <div id="deliveryFeeMessage" class="small text-muted mt-1">
                            Select your subdivision to calculate delivery fee.
                        </div>
                    </div>

                    <!-- These fields only show when PICKUP is selected -->
                    <div id="pickupDetailsFields" style="display:none;">
                        <label class="small fw-semibold mb-1">Customer Name:</label>
                        <input type="text" id="pickupName" class="form-control form-control-sm mb-2"
                            placeholder="Your full name">

                        <label class="small fw-semibold mb-1">Contact Number:</label>
                        <input type="text" id="pickupPhone" class="form-control form-control-sm mb-2"
                            placeholder="09XXXXXXXXX">

                        <div class="small text-muted mt-1">
                            We'll confirm your pickup order via text before preparing.
                        </div>
                    </div>
            </div>
        </div>



        <!-- CATEGORY SHORTCUTS -->
        <div class="d-flex flex-wrap gap-2 mb-5">
            <a href="#shawarma-wraps" class="btn btn-outline-dark btn-sm">Shawarma Wraps</a>
            <a href="#special-wraps" class="btn btn-outline-dark btn-sm">Special Wraps</a>
            <a href="#shawarma-rice" class="btn btn-outline-dark btn-sm">Shawarma Rice</a>
            <a href="#premium-steak-fries" class="btn btn-outline-dark btn-sm">Steak &amp; Fries</a>
            <a href="#fries-nachos" class="btn btn-outline-dark btn-sm">Coated Fries &amp; Nachos</a>
            <a href="#ala-carte" class="btn btn-outline-dark btn-sm">Ala Carte</a>
            <a href="#beverages-extras" class="btn btn-outline-dark btn-sm">Drinks</a>
        </div>

        <div class="row">
            <!-- MAIN MENU COLUMN -->
            <div class="col-lg-8">
                <!-- SHAWARMA WRAP -->
                <section id="shawarma-wraps" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Classic Shawarma Wrap</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Classic Shawarma Wrap (Beef / Chicken, Regular/Large, B1T1) -->
                        <div class="col-md-6 col-lg-6">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/classicshawarma.jpg"
                                         class="menu-card-img"
                                         alt="Shawarma Wrap">

                                    <div class="menu-card-gradient"></div>

                                    <div class="featured-tag badge bg-warning text-dark">
                                        Best Seller
                                    </div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Shawarma Wrap</h4>

                                        <!-- Starts at + modal trigger -->
                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱70</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="shawarma-wrap">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SPECIAL SHAWARMA WRAP -->
                <section id="special-wraps" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Special Shawarma Wrap</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Overload Wrap -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/overloadwrap.jpg"
                                         class="menu-card-img"
                                         alt="Overload Wrap">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Overload Wrap</h4>
                                        <p class="menu-card-text mb-2">
                                            Loaded with more meat, veggies and cheese.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱95</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="special-overload-wrap">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Double Cheese Wrap -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/doublecheesewrap.jpg"
                                         class="menu-card-img"
                                         alt="Double Cheese Wrap">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Double Cheese Wrap</h4>
                                        <p class="menu-card-text mb-2">
                                            Filled with sliced cheese and orange cheese.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱90</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="special-double-cheese-wrap">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Meat Wrap -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/allmeatwrap.jpg"
                                         class="menu-card-img"
                                         alt="All Meat Wrap">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">All Meat Wrap</h4>
                                        <p class="menu-card-text mb-2">
                                            Pure beef meat, no veggies.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱80</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="special-all-meat-wrap">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- SHAWARMA RICE -->
                <section id="shawarma-rice" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Shawarma Rice</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Regular Shawarma Rice -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/shawarmaveggiesrice.jpg"
                                        class="menu-card-img"
                                        alt="Shawarma Rice">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Shawarma Rice</h4>
                                        <p class="menu-card-text mb-2">
                                            Rice bowl topped with shawarma meat, veggies and sauces.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱95</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="shawarma-rice-regular">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Meat Shawarma Rice -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/allmeatshawarmarice.jpg"
                                        class="menu-card-img"
                                        alt="All Meat Shawarma Rice">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">All Meat Shawarma Rice</h4>
                                        <p class="menu-card-text mb-2">
                                            Rice bowl topped with pure beef, no veggies.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱105</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="shawarma-rice-all-meat">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- PREMIUM STEAK & FRIES -->
                <section id="premium-steak-fries" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Premium Steak &amp; Fries Shawarma</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Premium Steak & Fries (Regular / Large) -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/steakandfriesshawarma.jpg"
                                        class="menu-card-img"
                                        alt="Premium Steak & Fries">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Premium Steak &amp; Fries</h4>
                                        <p class="menu-card-text mb-2">
                                            Tray of fries topped with premium beef and sauces.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱100</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="premium-steak">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Double Cheese Premium Steak & Fries (Regular / Large) -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/doublecheese.jpg"
                                        class="menu-card-img"
                                        alt="Double Cheese Premium Steak & Fries">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Double Cheese Premium</h4>
                                        <p class="menu-card-text mb-2">
                                            Premium steak tray loaded with extra cheese.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱130</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="premium-steak-double-cheese">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- COATED FRIES & NACHOS -->
                <section id="fries-nachos" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Coated Fries &amp; Nachos</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Coated Fries -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/coatedfries.jpg"
                                        class="menu-card-img"
                                        alt="Coated Fries">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Coated Fries</h4>
                                        <p class="menu-card-text mb-2">
                                            Crispy fries in a flavored coating.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱98</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="coated-fries">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Beefy Coated Fries -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/beefyfries.jpg"
                                        class="menu-card-img"
                                        alt="Beefy Coated Fries">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Beefy Coated Fries</h4>
                                        <p class="menu-card-text mb-2">
                                            Coated fries topped with beef shawarma.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱120</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="beefy-coated-fries">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cheesy Beef Coated Fries -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/cheesyfries.jpg"
                                        class="menu-card-img"
                                        alt="Cheesy Beef Coated Fries">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Cheesy Beef Coated Fries</h4>
                                        <p class="menu-card-text mb-2">
                                            Beefy coated fries leveled up with extra cheese.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱150</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="cheesy-beef-coated-fries">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nacho Shawarma -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/nachos.jpg"
                                        class="menu-card-img"
                                        alt="Nacho Shawarma">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Nacho Shawarma</h4>
                                        <p class="menu-card-text mb-2">
                                            Nachos piled with beef, veggies and sauces.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱170</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="nacho-shawarma">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Nacho Bandido -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/nachobandido.jpg"
                                        class="menu-card-img"
                                        alt="Nacho Bandido">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Nacho Bandido</h4>
                                        <p class="menu-card-text mb-2">
                                            Another loaded nacho favorite from the depot.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱150</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="nacho-bandido">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- ALA CARTE -->
                <section id="ala-carte" class="mb-5 reveal-on-scroll">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <div>
                            <h2 class="h4 section-heading mb-0">Shawarma Ala Carte Plates</h2>
                        </div>
                    </div>

                    <div class="row g-4 align-items-stretch">
                        <!-- Meat & Veggies -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/shawarmacarte.jpg"
                                        class="menu-card-img"
                                        alt="Meat & Veggies Ala Carte">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Meat &amp; Veggies</h4>
                                        <p class="menu-card-text mb-2">
                                            Shawarma meat with veggies and sauces on a plate.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱210</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="ala-meat-veggies">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- All Meat -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/allmeat.jpg"
                                        class="menu-card-img"
                                        alt="All Meat Ala Carte">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">All Meat</h4>
                                        <p class="menu-card-text mb-2">
                                            Pure shawarma meat served on a plate.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱240</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="ala-all-meat">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- BEVERAGES-->
                <section id="beverages-extras" class="mb-3 reveal-on-scroll">
                        <div>
                            <h2 class="h4 section-heading mb-0">Beverages</h2>
                        </div>
                        <br>
                    <div class="row g-4 align-items-stretch">
                        <!-- Beverages -->
                        <div class="col-md-6 col-lg-4">
                            <div class="card menu-card menu-card-overlay h-100 border-0">
                                <div class="menu-card-image-wrapper">
                                    <img src="assets/images/drinks.jpg"
                                        class="menu-card-img"
                                        alt="Beverages">
                                    <div class="menu-card-gradient"></div>

                                    <div class="menu-card-content">
                                        <h4 class="menu-card-title mb-1">Beverages</h4>
                                        <p class="menu-card-text mb-2">
                                            Refreshing drinks to go with your shawarma.
                                        </p>

                                        <div class="small mb-2 d-flex justify-content-between align-items-center">
                                            <span class="text-white-50">
                                                Starts at <span class="price-tag">₱25</span>
                                            </span>

                                            <button
                                                class="btn btn-warning btn-sm fw-semibold js-open-variants"
                                                type="button"
                                                data-product-id="drinks">
                                                <i class="fa-solid fa-plus me-1"></i>
                                                Details
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <p class="text-muted small mt-4">
                    * Menu and prices based on Shawarma Depot's current offerings. Items and pricing may change depending on availability and promos.
                </p>
            </div>

            <!-- SIDE CART COLUMN (desktop only) -->
            <div class="col-lg-4 d-none d-lg-block">
                <div class="card shadow-sm sticky-top mb-4" style="top: 90px;">
                    <div class="card-body">
                        <h5 class="card-title mb-3 d-flex justify-content-between align-items-center">
                            <span><i class="fa-solid fa-cart-shopping me-2"></i> Your Order</span>
                            <span class="badge bg-warning text-dark" id="cartItemCount">0</span>
                        </h5>

                        <div id="cartItems" class="mb-3 text-muted">
                            No items yet. Come try out what we have!
                        </div>

                        <hr class="my-2">

                        <div class="small text-muted mb-2" id="cartFulfillmentNote">
                            Delivery fee will be calculated based on your address.
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Subtotal</span>
                            <span id="cartSubtotal">₱0</span>
                        </div>
                        <div class="d-flex justify-content-between small" id="cartDeliveryRow" style="display:none;">
                            <span>Delivery fee</span>
                            <span id="cartDeliveryFee">₱0</span>
                        </div>
                        <div class="d-flex justify-content-between fw-semibold mt-2">
                            <span>Total</span>
                            <span id="cartTotal">₱0</span>
                        </div>

                        <div id="menuCheckoutErrors" class="alert alert-danger small d-none mt-2 mb-0"></div>

                        <button type="button"
                                id="btnProceedCheckout"
                                class="btn btn-warning w-100 btn-sm fw-semibold mt-2">
                            <i class="fa-solid fa-bag-shopping me-1"></i>
                            Proceed to Checkout
                        </button>
                    </div>
                </div>
            </div>
        </div>
</main>


<!-- PRODUCT VARIANT MODAL (used for Shawarma Wrap options) -->
<div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModalLabel">Choose option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <p class="small text-muted mb-3" id="variantModalDescription"></p>

                <div id="variantModalOptions" class="list-group small">
                    <!-- JS injects Shawarma Wrap options here -->
                </div>
            </div>

            <div class="modal-footer justify-content-between">
                <div class="small text-muted" id="variantModalPriceHint">
                    Pick a size/deal to see the price.
                </div>
                <button type="button" class="btn btn-warning btn-sm fw-semibold" id="variantModalAddBtn">
                    <i class="fa-solid fa-cart-plus me-1"></i>
                    Add to cart
                </button>
            </div>
        </div>
    </div>
</div>


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

<!-- MOBILE ORDER BAR -->
<div class="d-lg-none fixed-bottom bg-dark text-light py-2 shadow mobile-order-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="small">
            <div class="fw-semibold">Order Shawarma Now</div>
            <div class="small text-muted">Open 4:00 PM – 11:00 PM</div>
        </div>
        <a href="menu.php" class="btn btn-warning btn-sm fw-semibold">
            <i class="fa-solid fa-bag-shopping me-1"></i> View Cart
        </a>
    </div>
</div>

<!-- BACK TO TOP -->
<button class="btn btn-warning back-to-top" type="button">
    <i class="fa-solid fa-arrow-up"></i>
</button>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>

<!-- Custom JS -->
<script type="module" src="assets/js/main.js"></script>

</body>
</html>
