<?php
// index.php - client landing page for Shawarma Depot
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Shawarma Depot | Order Fresh Shawarma Online</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

    <!-- Font Awesome (icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="menu.php">Menu</a>
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


<!-- HERO SECTION -->
<section class="hero-section position-relative overflow-hidden">
    <!-- Background slider-->
    <div class="hero-bg">
        <div class="hero-bg-slide" style="background-image: url('assets/images/allmeatshawarmarice.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/steakandfriesshawarma.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/chickenshawarmarice.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/loadedfries.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/nachos.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/shawarmacarte.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/shawarmarice.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/steakandfries.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/superalacarteshawarma.jpg');"></div>
        <div class="hero-bg-slide" style="background-image: url('assets/images/shawarmaveggiesrice.jpg');"></div>
    </div>
    <div class="hero-overlay"></div>

    <div class="container position-relative py-5">
        <div class="row align-items-center gy-4">
            <!-- Hero Text -->
            <div class="col-lg-7 text-lg-start text-center">
                <div class="hero-badge mb-3 d-inline-flex align-items-center px-3 py-1 rounded-pill bg-dark bg-opacity-75 text-light small">
                    <i class="fa-solid fa-location-dot me-2 text-warning"></i>
                    Local Favorite in <strong class="ms-1">San Marino City</strong>!
                </div>

                <h1 class="hero-title mb-3">
                    The Soul of<br>Real Shawarma.
                </h1>

                <p class="hero-subtitle mb-4">
                    Quality Beef Shawarma Starts Here.<br>
                    Available for pickup and delivery!
                </p>

                <div class="d-flex flex-wrap justify-content-lg-start justify-content-center gap-2 mb-4">
                    <a href="menu.php" class="btn btn-warning btn-lg fw-semibold px-4">
                        <i class="fa-solid fa-bag-shopping me-2"></i> Order Now
                    </a>
                    <a href="#best-sellers" class="btn btn-outline-light btn-lg px-4">
                        <i class="fa-solid fa-fire me-2"></i> View Bestsellers
                    </a>
                </div>

                <div class="d-flex flex-wrap justify-content-lg-start justify-content-center hero-stats text-center text-lg-start">
                    <div class="me-lg-4 mb-2">
                        <h6 class="mb-0 fw-bold text-light">Everyday</h6>
                        <small class="text-light opacity-75">
                            Open Everyday:<br>4:00 PM – 11:00 PM
                        </small>
                    </div>
                    <div class="mb-2">
                        <h6 class="mb-0 fw-bold text-light">Cash &amp; GCash</h6>
                        <small class="text-light opacity-75">
                            Simple, hassle-free payment
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- QUICK LINKS STRIP -->
<section class="py-3 bg-light border-bottom">
    <div class="container">
        <div class="row g-3 text-center text-md-start">
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="icon-circle me-2">
                        <i class="fa-solid fa-fire"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Craving something heavy?</div>
                        <a href="#best-sellers" class="fw-semibold small">Check our Best Sellers</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 border-md-start border-md-end">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="icon-circle me-2">
                        <i class="fa-solid fa-star"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Rated by real customers</div>
                        <a href="#reviews" class="fw-semibold small">See Facebook reviews</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="icon-circle me-2">
                        <i class="fa-solid fa-location-dot"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Want to drop by?</div>
                        <a href="#visit-us" class="fw-semibold small">View location & hours</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BEST SELLERS -->
<section id="best-sellers" class="py-5 section-deals reveal-on-scroll">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-title"><h1 class="hero-title mb-3">
                    BEST SELLERS
                </h1></div>
        </div>

        <div class="row g-4 align-items-stretch">
            <!-- Featured card -->
            <div class="col-lg-6">
                <div class="card menu-card menu-card-overlay h-100 border-0">
                    <div class="menu-card-image-wrapper">
                        <img src="assets/images/doublecheese.jpg" class="menu-card-img" alt="Double Cheese Premium Steak and Fries Shawarma">

                        <div class="menu-card-gradient"></div>

                        <div class="featured-tag badge bg-warning text-dark">
                            Best Seller
                        </div>

                        <div class="menu-card-content">
                            <h4 class="menu-card-title mb-1">Double Cheese Premium Steak and Fries Shawarma</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag mb-0">₱ 130 / 155 [Regular/Large]</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Featured card 2 -->
            <div class="col-lg-6">
                <div class="card menu-card menu-card-overlay h-100 border-0">
                    <div class="menu-card-image-wrapper">
                        <img src="assets/images/classicshawarma.jpg" class="menu-card-img" alt="Beef Shawarma">

                        <div class="menu-card-gradient"></div>

                        <div class="featured-tag badge bg-warning text-dark">
                            Best Seller
                        </div>

                        <div class="menu-card-content">
                            <h4 class="menu-card-title mb-1">Beef Shawarma</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag mb-0">₱ 70 / 85 [Regular/Large]</span>
                                <span class="price-tag mb-0">₱ 135 / 165 [B1T1 Regular/Large]</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured card 3 -->
            <div class="col-lg-6">
                <div class="card menu-card menu-card-overlay h-100 border-0">
                    <div class="menu-card-image-wrapper">
                        <img src="assets/images/shawarmaveggiesrice.jpg" class="menu-card-img" alt="Shawarma Rice Overload">

                        <div class="menu-card-gradient"></div>

                        <div class="featured-tag badge bg-warning text-dark">
                            Best Seller
                        </div>

                        <div class="menu-card-content">
                            <h4 class="menu-card-title mb-1">Shawarma Rice Overload</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag mb-0">₱ 150</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Featured card 4 -->
            <div class="col-lg-6">
                <div class="card menu-card menu-card-overlay h-100 border-0">
                    <div class="menu-card-image-wrapper">
                        <img src="assets/images/loadedfries.jpg" class="menu-card-img" alt="Coated Fries">

                        <div class="menu-card-gradient"></div>

                        <div class="featured-tag badge bg-warning text-dark">
                            Best Seller
                        </div>

                        <div class="menu-card-content">
                            <h4 class="menu-card-title mb-1">Coated Fries</h4>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="price-tag mb-0">₱ 90 / 190 [Solo/Bucket]</span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="menu.php" class="btn btn-dark px-4">
                <i class="fa-solid fa-list-ul me-2"></i> View Full Menu
            </a>
        </div>
    </div>
</section>

<!-- CUSTOMER REVIEWS -->
<section id="reviews" class="py-5 bg-dark text-light reveal-on-scroll">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-title text-warning"><h1 class="hero-title mb-3">
                    CUSTOMER REVIEWS
                </h1></div>
            <p class="text-light opacity-75 mb-1">
                Real feedback from customers who tried our shawarma.
            </p>
        </div>

        <div class="row g-4">
            <!-- Review 1 -->
            <div class="col-md-6">
                <div class="card review-card border-0 shadow-sm h-100">
                    <div class="card-body p-3 p-sm-4">
                        <div class="review-box">
                            <div class="d-flex align-items-center mb-2">
                                <div class="review-avatar me-2 me-sm-3">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small">Customer Review</div>
                                    <div class="text-muted small">From Facebook</div>
                                </div>
                            </div>

                            <iframe 
                                src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fjonahbels0220%2Fposts%2Fpfbid02vdCxdWugB9e4zZS2yzPL2DukcSc9tPruEEJyKmX7UgfygMze84GsJKGCTHsqK6Mcl&show_text=true&width=500"
                                style="border:none;overflow:hidden;width:100%;height:230px;"
                                scrolling="no"
                                frameborder="0"
                                allowfullscreen="true"
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="col-md-6">
                <div class="card review-card border-0 shadow-sm h-100">
                    <div class="card-body p-3 p-sm-4">
                        <div class="review-box">
                            <div class="d-flex align-items-center mb-2">
                                <div class="review-avatar me-2 me-sm-3">
                                    <i class="fa-brands fa-facebook-f"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold small">Customer Review</div>
                                    <div class="text-muted small">From Facebook</div>
                                </div>
                            </div>

                            <iframe 
                                src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2Fsongaliashan%2Fposts%2Fpfbid02bqQ6Brma8uG7Hi7dc7bqHzCCu5nYuondmFPfW97Xj7oFmCFnS8gi4mkm99G9MjH8l&show_text=true&width=500" 
                                style="border:none;overflow:hidden;width:100%;height:230px;"
                                scrolling="no" 
                                frameborder="0" 
                                allowfullscreen="true" 
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FROM FACEBOOK (REELS) -->
<section class="py-5 section-deals reveal-on-scroll">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-5">
                <h2 class="section-heading mb-3"><h1 class="hero-title mb-3">
                    Straight from <br>the grill
                </h1></h2>
                <p class="text-muted mb-3"  >
                    Reels straight from Shawarma Depot's Facebook page.
                </p>
                <p class="small text-muted mb-3">
                    Follow us on Facebook for promos, new items, and behind-the-scenes shawarma content.
                </p>
                <a href="https://www.facebook.com/p/Shawarma-Depot-61564260277953" target="_blank" class="btn btn-dark btn-sm">
                    <i class="fa-brands fa-facebook-f me-1"></i> Visit Facebook Page
                </a>
            </div>

            <div class="col-lg-7">
                <div class="bg-dark text-light rounded-4 p-3 p-lg-4 shadow-lg hero-video-card">
                    <h5 class="fw-bold mb-3">
                        <i class="fa-solid fa-play text-warning me-2"></i> Featured Reels
                    </h5>

                    <div id="fbReelsCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">

                            <!-- Reel 1 -->
                            <div class="carousel-item active">
                                <div class="hero-fb-video">
                                    <iframe 
                                        src="https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/reel/585265031225394/&show_text=false&mute=1"
                                        style="border:none;overflow:hidden"
                                        scrolling="no"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media; clipboard-write; picture-in-picture; web-share"
                                        allowfullscreen="true">
                                    </iframe>
                                </div>
                                <p class="small mt-2 opacity-75">Loaded Chips Available at Shawarma Depot!</p>
                            </div>

                            <!-- Reel 2 -->
                            <div class="carousel-item">
                                <div class="hero-fb-video">
                                    <iframe 
                                        src="https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/reel/615205121432866/&show_text=false&mute=1"
                                        style="border:none;overflow:hidden"
                                        scrolling="no"
                                        frameborder="0"
                                        allow="autoplay; encrypted-media; clipboard-write; picture-in-picture; web-share"
                                        allowfullscreen="true">
                                    </iframe>
                                </div>
                                <p class="small mt-2 opacity-75">Our Premium Steak & Fries Shawarma</p>
                            </div>
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#fbReelsCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>

                        <button class="carousel-control-next" type="button" data-bs-target="#fbReelsCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>

                    <a href="menu.php" class="btn btn-warning w-100 mt-3 fw-semibold">
                        <i class="fa-solid fa-bag-shopping me-1"></i> Order Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LOCATION & HOURS -->
<section id="visit-us" class="py-5 visit-section">
    <div class="container">
        <div class="row gy-4 align-items-center">
            <div class="col-lg-6">
                <div class="section-title">Visit Us</div>
                <h2 class="section-heading mb-3">Find Shawarma Depot</h2>
                <p class="text-muted mb-4">
                    We're a local spot with big flavor. Drop by the stall or order online and chill while we prep your food.
                </p>

                <div class="location-card p-4 mb-3 bg-white rounded-4 shadow-sm">
                    <h6 class="fw-bold mb-2">
                        <i class="fa-solid fa-location-dot me-2 text-warning"></i> Store Location <br><br>
                    </h6>
                    <!-- exact address pulled from FB page -->
                    <p class="mb-1">
                        Blk 16 Lot 26 Laos St Corner Lebanon St
                        San Marino City, Cavite
                    </p>
                </div>

                <div class="row">
                    <div class="col-sm-6 mb-3">
                        <h6 class="fw-bold mb-2">
                            <i class="fa-solid fa-clock me-2 text-warning"></i> Opening Hours
                        </h6>
                        <ul class="list-unstyled opening-hours small text-muted mb-0">
                            <!-- actual schedule -->
                            <li><span>Everyday</span> <span>4:00 PM – 11:00 PM</span></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 mb-3">
                        <h6 class="fw-bold mb-2">
                            <i class="fa-solid fa-phone me-2 text-warning"></i> Contact
                        </h6>
                        <!-- phone number / FB link -->
                        <p class="small mb-1 text-muted">
                            Email: <strong>shawarmadepot2021@gmail.com</strong>
                        </p>
                        <p class="small mb-2 text-muted mb-0">
                            Facebook: <a href="https://www.facebook.com/p/Shawarma-Depot-61564260277953" target="_blank">Shawarma Depot</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- map image -->
            <div class="col-lg-6">
                <div class="ratio ratio-16x9 rounded-4 overflow-hidden shadow-sm">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1932.8790160741405!2d120.9786778244057!3d14.32548291342115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d42dc98af931%3A0x7e9daa52ac95816b!2sSHAWARMA%20DEPOT!5e0!3m2!1sen!2sph!4v1763565541382!5m2!1sen!2sph"
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
    <p class="small text-muted mt-2">
        Find us here on Google Maps — delivery & pickup available.
    </p>
</div>

        </div>
    </div>
</section>

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


<!-- MOBILE STICKY ORDER BAR -->
<div class="d-lg-none fixed-bottom bg-dark text-light py-2 shadow mobile-order-bar">
    <div class="container d-flex justify-content-between align-items-center">
        <div class="small">
            <div class="fw-semibold">Order Shawarma Now</div>
            <div class="small text-muted">Open 4:00 PM – 11:00 PM</div>
        </div>
        <a href="menu.php" class="btn btn-warning btn-sm fw-semibold">
            <i class="fa-solid fa-bag-shopping me-1"></i> Order
        </a>
    </div>
</div>

<!-- BACK TO TOP BUTTON -->
<button class="btn btn-warning back-to-top" type="button">
    <i class="fa-solid fa-arrow-up"></i>
</button>


<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

<!-- custom JS -->
<script type="module" src="assets/js/main.js"></script>

</body>
</html>
