<?php
// menu.php - full menu (frontend only)
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
                    <a class="btn btn-outline-warning fw-semibold px-3" href="cart.php">
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

        <!-- CATEGORY SHORTCUTS -->
        <div class="d-flex flex-wrap gap-2 mb-5">
            <a href="#shawarma-wraps" class="btn btn-outline-dark btn-sm">Shawarma Wraps</a>
            <a href="#special-wraps" class="btn btn-outline-dark btn-sm">Special Wraps</a>
            <a href="#shawarma-rice" class="btn btn-outline-dark btn-sm">Shawarma Rice</a>
            <a href="#premium-steak-fries" class="btn btn-outline-dark btn-sm">Steak &amp; Fries</a>
            <a href="#fries-nachos" class="btn btn-outline-dark btn-sm">Coated Fries &amp; Nachos</a>
            <a href="#ala-carte" class="btn btn-outline-dark btn-sm">Ala Carte</a>
            <a href="#beverages-extras" class="btn btn-outline-dark btn-sm">Drinks &amp; Extras</a>
        </div>

        <!-- SHAWARMA WRAP -->
        <section id="shawarma-wraps" class="mb-5 reveal-on-scroll">
            <div class="d-flex justify-content-between align-items-end mb-3">
                <div>
                    <p class="section-title mb-1">Shawarma Wrap</p>
                    <h2 class="h4 section-heading mb-0">Classic Wraps</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/beefshawarma.jpg"
                                 class="menu-card-img"
                                 alt="Shawarma Wrap">

                            <div class="menu-card-gradient"></div>

                            <div class="featured-tag badge bg-warning text-dark">
                                Best Seller
                            </div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Shawarma Wrap</h4>

                                <!-- We only show "starts at" here to keep the card clean.
                                     Full options (Large, Regular, B1T1, etc.) are shown in the modal. -->
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
                    <p class="section-title mb-1">Special Shawarma Wrap</p>
                    <h2 class="h4 section-heading mb-0">Fully Loaded Wraps</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Overload Wrap -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/overload-wrap.jpg"
                                 class="menu-card-img"
                                 alt="Overload Wrap">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Overload Wrap</h4>
                                <p class="menu-card-text mb-2">
                                    Loaded with more meat, veggies and cheese.
                                </p>

                                <div class="small mb-2">
                                    <div>Regular – <span class="price-tag">₱95</span></div>
                                    <div>Large – <span class="price-tag">₱110</span></div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-1">
                                    <span class="small text-white-50">Starts at <span class="price-tag">₱95</span></span>
                                    <button class="btn btn-warning btn-sm fw-semibold mt-1 mt-sm-0" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add to cart
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
                            <img src="assets/images/double-cheese-wrap.jpg"
                                 class="menu-card-img"
                                 alt="Double Cheese Wrap">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Double Cheese Wrap</h4>
                                <p class="menu-card-text mb-2">
                                    Filled with sliced cheese and orange cheese.
                                </p>

                                <div class="small mb-2">
                                    <div>Regular – <span class="price-tag">₱90</span></div>
                                    <div>Large – <span class="price-tag">₱105</span></div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-1">
                                    <span class="small text-white-50">Starts at <span class="price-tag">₱90</span></span>
                                    <button class="btn btn-warning btn-sm fw-semibold mt-1 mt-sm-0" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add to cart
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
                            <img src="assets/images/all-meat-wrap.jpg"
                                 class="menu-card-img"
                                 alt="All Meat Wrap">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">All Meat Wrap</h4>
                                <p class="menu-card-text mb-2">
                                    Pure beef meat, no veggies.
                                </p>

                                <div class="small mb-2">
                                    <div>Regular – <span class="price-tag">₱80</span></div>
                                    <div>Large – <span class="price-tag">₱95</span></div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-1">
                                    <span class="small text-white-50">Starts at <span class="price-tag">₱80</span></span>
                                    <button class="btn btn-warning btn-sm fw-semibold mt-1 mt-sm-0" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add to cart
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
                    <p class="section-title mb-1">Shawarma Rice</p>
                    <h2 class="h4 section-heading mb-0">Rice Meals</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Shawarma Rice -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/shawarma-rice.jpg"
                                 class="menu-card-img"
                                 alt="Shawarma Rice">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Shawarma Rice</h4>
                                <p class="menu-card-text mb-2">
                                    Rice bowl topped with beef shawarma, veggies and sauces.
                                </p>

                                <div class="small mb-2">
                                    <div>Solo – <span class="price-tag">₱95</span></div>
                                    <div>Overload – <span class="price-tag">₱150</span></div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-1">
                                    <span class="small text-white-50">Starts at <span class="price-tag">₱95</span></span>
                                    <button class="btn btn-warning btn-sm fw-semibold mt-1 mt-sm-0" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add to cart
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
                            <img src="assets/images/all-meat-rice.jpg"
                                 class="menu-card-img"
                                 alt="All Meat Shawarma Rice">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">All Meat Shawarma Rice</h4>
                                <p class="menu-card-text mb-2">
                                    Rice bowl topped with pure beef, no veggies.
                                </p>

                                <div class="small mb-2">
                                    <div>Solo – <span class="price-tag">₱105</span></div>
                                    <div>Overload – <span class="price-tag">₱165</span></div>
                                </div>

                                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center gap-1">
                                    <span class="small text-white-50">Starts at <span class="price-tag">₱105</span></span>
                                    <button class="btn btn-warning btn-sm fw-semibold mt-1 mt-sm-0" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add to cart
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
                    <p class="section-title mb-1">Premium Steak &amp; Fries</p>
                    <h2 class="h4 section-heading mb-0">Steak Trays</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Large Premium -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/steak-large.jpg"
                                 class="menu-card-img"
                                 alt="Large Premium Steak & Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Large Premium</h4>
                                <p class="menu-card-text mb-2">
                                    Large tray of fries topped with premium beef and sauces.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱125</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Regular Premium -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/steak-regular.jpg"
                                 class="menu-card-img"
                                 alt="Regular Premium Steak & Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Regular Premium</h4>
                                <p class="menu-card-text mb-2">
                                    Regular-sized tray with premium beef and sauces.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱100</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Double Cheese Premium -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/steak-double-cheese.jpg"
                                 class="menu-card-img"
                                 alt="Double Cheese Premium Steak & Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Double Cheese Premium</h4>
                                <p class="menu-card-text mb-2">
                                    Premium steak tray loaded with extra cheese.
                                </p>

                                <div class="small mb-2">
                                    <div>Large – <span class="price-tag">₱155</span></div>
                                    <div>Regular – <span class="price-tag">₱130</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                    <p class="section-title mb-1">Coated Fries &amp; Nachos</p>
                    <h2 class="h4 section-heading mb-0">Loaded Sides</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Coated Fries -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/coated-fries.jpg"
                                 class="menu-card-img"
                                 alt="Coated Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Coated Fries</h4>
                                <p class="menu-card-text mb-2">
                                    Crispy fries in a flavored coating.
                                </p>

                                <div class="small mb-2">
                                    <div>Solo – <span class="price-tag">₱98</span></div>
                                    <div>Bucket – <span class="price-tag">₱190</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                            <img src="assets/images/beefy-coated-fries.jpg"
                                 class="menu-card-img"
                                 alt="Beefy Coated Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Beefy Coated Fries</h4>
                                <p class="menu-card-text mb-2">
                                    Coated fries topped with beef shawarma.
                                </p>

                                <div class="small mb-2">
                                    <div>Solo – <span class="price-tag">₱120</span></div>
                                    <div>Bucket – <span class="price-tag">₱215</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                            <img src="assets/images/cheesy-beef-fries.jpg"
                                 class="menu-card-img"
                                 alt="Cheesy Beef Coated Fries">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Cheesy Beef Coated Fries</h4>
                                <p class="menu-card-text mb-2">
                                    Beefy coated fries leveled up with extra cheese.
                                </p>

                                <div class="small mb-2">
                                    <div>Solo – <span class="price-tag">₱150</span></div>
                                    <div>Bucket – <span class="price-tag">₱290</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                            <img src="assets/images/nacho-shawarma.jpg"
                                 class="menu-card-img"
                                 alt="Nacho Shawarma">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Nacho Shawarma</h4>
                                <p class="menu-card-text mb-2">
                                    Nachos piled with beef, veggies and sauces.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱170</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                            <img src="assets/images/nacho-bandido.jpg"
                                 class="menu-card-img"
                                 alt="Nacho Bandido">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Nacho Bandido</h4>
                                <p class="menu-card-text mb-2">
                                    Another loaded nacho favorite from the depot.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱150</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                    <p class="section-title mb-1">Shawarma Ala Carte</p>
                    <h2 class="h4 section-heading mb-0">Plates</h2>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <!-- Meat & Veggies -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/alacarte-meat-veggies.jpg"
                                 class="menu-card-img"
                                 alt="Meat & Veggies Ala Carte">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Meat &amp; Veggies</h4>
                                <p class="menu-card-text mb-2">
                                    Shawarma meat with veggies and sauces on a plate.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱210</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
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
                            <img src="assets/images/alacarte-all-meat.jpg"
                                 class="menu-card-img"
                                 alt="All Meat Ala Carte">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">All Meat</h4>
                                <p class="menu-card-text mb-2">
                                    Pure shawarma meat served on a plate.
                                </p>

                                <div class="small mb-2">
                                    <div>Price – <span class="price-tag">₱240</span></div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-warning btn-sm fw-semibold" type="button">
                                        <i class="fa-solid fa-plus me-1"></i>
                                        Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- BEVERAGES & EXTRAS -->
        <section id="beverages-extras" class="mb-3 reveal-on-scroll">
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

                                <div class="small mb-2">
                                    <div>Plum Tea Regular – <span class="price-tag">₱60</span></div>
                                    <div>Plum Tea Large – <span class="price-tag">₱70</span></div>
                                    <div>Coke Mismo – <span class="price-tag">₱25</span></div>
                                    <div>Royal Mismo – <span class="price-tag">₱25</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Extras -->
                <div class="col-md-6 col-lg-4">
                    <div class="card menu-card menu-card-overlay h-100 border-0">
                        <div class="menu-card-image-wrapper">
                            <img src="assets/images/extras.jpg"
                                 class="menu-card-img"
                                 alt="Extras">
                            <div class="menu-card-gradient"></div>

                            <div class="menu-card-content">
                                <h4 class="menu-card-title mb-1">Extras</h4>
                                <p class="menu-card-text mb-2">
                                    Level up your wrap, rice, or fries with add-ons.
                                </p>

                                <div class="small mb-2">
                                    <div>Meat – <span class="price-tag">₱30</span></div>
                                    <div>Rice – <span class="price-tag">₱20</span></div>
                                    <div>Veggies – <span class="price-tag">₱20</span></div>
                                    <div>Garlic Sauce – <span class="price-tag">₱15</span></div>
                                    <div>Cheese Sauce – <span class="price-tag">₱15</span></div>
                                    <div>Sliced Cheese – <span class="price-tag">₱15</span></div>
                                    <div>Orange Cheese – <span class="price-tag">₱30</span></div>
                                    <div>Salsa – <span class="price-tag">₱20</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <p class="text-muted small mt-4">
            * Menu and prices based on Shawarma Depot’s current offerings. Items and pricing may change depending on availability and promos.
        </p>
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
                <button type="button" class="btn btn-warning btn-sm fw-semibold">
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
        <a href="cart.php" class="btn btn-warning btn-sm fw-semibold">
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
<script src="assets/js/main.js"></script>

</body>
</html>
