<?php
// about.php - About Shawarma Depot (frontend)
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>About | Shawarma Depot</title>
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
                aria-controls="mainNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
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
                    <a class="nav-link active" aria-current="page" href="about.php">About</a>
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

<main>
    <!-- ABOUT SECTION -->
    <section class="py-5 section-info">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-6">
                    <div class="section-title mb-1 text-uppercase">
                        About Us
                    </div>
                    <h1 class="section-heading h3 mb-3">
                        The Soul of Real Shawarma
                    </h1>
                    <p class="text-muted">
                        Shawarma Depot started serving at San Marino City, Dasmariñas on
                        <strong>November 25, 2021</strong> with one simple goal:
                        bring the feel of <strong>real shawarma</strong> closer to the neighborhood —
                        no shortcuts, no nonsense.
                    </p>
                    <p class="text-muted mb-3">
                        From late-night cravings to family merienda, we’ve become a small
                        go-to spot for beef shawarma, loaded wraps, shawarma rice, and trays
                        made to share. We focus on consistent flavor, fair pricing, and
                        easy ordering whether you walk up to the stall or order for delivery.
                    </p>

                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fa-solid fa-cow"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Quality beef shawarma</div>
                                    <div class="small text-muted">
                                        Marinated and cooked for flavor, not just speed.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fa-solid fa-bowl-food"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Food stall & delivery</div>
                                    <div class="small text-muted">
                                        Grab a quick wrap or have it brought to your gate.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fa-solid fa-people-group"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">For the neighborhood</div>
                                    <div class="small text-muted">
                                        Built for San Marino and nearby areas first.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="d-flex align-items-start">
                                <div class="feature-icon me-3">
                                    <i class="fa-solid fa-truck-fast"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Easy to order</div>
                                    <div class="small text-muted">
                                        Message, call, or use this web ordering system.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="small text-muted">
                        Category: Food stall · Food & drink · Food delivery service
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card location-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-location-dot me-2 text-warning"></i>
                                Where to find us
                            </h5>

                            <p class="mb-2">
                                Blk 16 Lot 26 Laos St Corner Lebanon St<br>
                                San Marino City, Dasmariñas, Cavite, 4114
                            </p>

                            <p class="text-muted small mb-3">
                                Open now (hours may vary on holidays). For the latest schedule,
                                check our Facebook page or send us a message.
                            </p>

                            <ul class="list-unstyled small mb-3">
                                <li class="mb-1">
                                    <i class="fa-solid fa-envelope me-2 text-warning"></i>
                                    <a href="mailto:shawarmadepot2021@gmail.com">
                                        shawarmadepot2021@gmail.com
                                    </a>
                                </li>
                                <li class="mb-1">
                                    <i class="fa-brands fa-facebook me-2 text-primary"></i>
                                    <a href="https://www.facebook.com/p/Shawarma-Depot-61564260277953"
                                       target="_blank" rel="noopener">
                                        Shawarma Depot on Facebook
                                    </a>
                                </li>
                            </ul>

                            <div class="ratio ratio-4x3 mb-2">
                                <!-- Replace src with actual Google Maps embed when ready -->
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1932.8790160741405!2d120.9786778244057!3d14.32548291342115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d42dc98af931%3A0x7e9daa52ac95816b!2sSHAWARMA%20DEPOT!5e0!3m2!1sen!2sph!4v1763565541382!5m2!1sen!2sph"
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                            <div class="small text-muted">
                                Map is a placeholder. You can replace it later with the exact
                                Google Maps embed link once confirmed.
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

<!-- FOOTER -->
<footer class="footer mt-5">
    <div class="container">
        <div class="row gy-3 align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <small class="d-block">
                    &copy; <?php echo date('Y'); ?> Shawarma Depot. All rights reserved.
                </small>
                <small class="text-muted">
                    The Soul of Real Shawarma – quality beef shawarma starts here.
                </small>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <small class="me-2">Follow us:</small>
                <a href="https://www.facebook.com/p/Shawarma-Depot-61564260277953"
                   target="_blank" rel="noopener" class="me-2">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="#"><i class="fa-brands fa-instagram"></i></a>
            </div>
        </div>
    </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script src="js/main.js"></script>
</body>
</html>
