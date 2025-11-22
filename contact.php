<?php
// contact.php - Contact page (frontend, backend-ready form)
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact | Shawarma Depot</title>
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

<main>
    <!-- CONTACT SECTION -->
    <section class="py-5 section-info">
        <div class="container">
            <div class="mb-4 text-center text-md-start">
                <div class="section-title mb-1 text-uppercase">
                    Contact
                </div>
                <h1 class="section-heading h3 mb-2">
                    Talk to Shawarma Depot
                </h1>
                <p class="text-muted mb-0">
                    Questions, feedback, or order concerns? Send us a message here or through our
                    official Facebook page.
                </p>
            </div>

            <div class="row g-4">
                <!-- Left: contact form -->
                <div class="col-lg-7">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-envelope-open-text me-2 text-warning"></i>
                                Send us a message
                            </h5>
                            <p class="small text-muted mb-3">
                                For urgent order updates, it’s still best to message our Facebook page directly.
                                This form is for general questions, feedback, or anything you want us to know.
                            </p>

                            <!-- 
                                Backend note:
                                Later, set action="contact-submit.php" or similar and process via PHP.
                             -->
                            <form action="" method="post">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase text-muted" for="contactName">
                                            Name
                                        </label>
                                        <input type="text"
                                               class="form-control"
                                               id="contactName"
                                               name="name"
                                               placeholder="Your name"
                                               required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase text-muted" for="contactPhone">
                                            Mobile Number
                                        </label>
                                        <input type="tel"
                                               class="form-control"
                                               id="contactPhone"
                                               name="phone"
                                               placeholder="09XX XXX XXXX"
                                               required>
                                    </div>
                                </div>

                                <div class="row g-3 mt-1">
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase text-muted" for="contactEmail">
                                            Email <span class="text-muted">(optional)</span>
                                        </label>
                                        <input type="email"
                                               class="form-control"
                                               id="contactEmail"
                                               name="email"
                                               placeholder="you@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small text-uppercase text-muted" for="contactType">
                                            Reason for contact
                                        </label>
                                        <select class="form-select" id="contactType" name="type" required>
                                            <option value="">Choose one...</option>
                                            <option value="order_concern">Order concern</option>
                                            <option value="feedback">Feedback / suggestion</option>
                                            <option value="menu_question">Menu / pricing question</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label class="form-label small text-uppercase text-muted" for="contactMessage">
                                        Message
                                    </label>
                                    <textarea class="form-control"
                                              id="contactMessage"
                                              name="message"
                                              rows="4"
                                              placeholder="Tell us more..."
                                              required></textarea>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <small class="text-muted">
                                        We’ll get back to you as soon as we can, usually through
                                        Messenger or SMS.
                                    </small>
                                    <button type="submit" class="btn btn-warning fw-semibold text-dark">
                                        Send Message
                                        <i class="fa-solid fa-paper-plane ms-1"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right: direct contact info -->
                <div class="col-lg-5">
                    <div class="card location-card mb-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3">
                                <i class="fa-solid fa-headset me-2 text-warning"></i>
                                Direct contact
                            </h5>

                            <ul class="list-unstyled small mb-3">
                                <li class="mb-2">
                                    <i class="fa-solid fa-envelope me-2 text-warning"></i>
                                    <a href="mailto:shawarmadepot2021@gmail.com">
                                        shawarmadepot2021@gmail.com
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <i class="fa-brands fa-facebook me-2 text-primary"></i>
                                    <a href="https://www.facebook.com/p/Shawarma-Depot-61564260277953"
                                       target="_blank" rel="noopener">
                                        Shawarma Depot (Facebook page)
                                    </a>
                                </li>
                            </ul>

                            <h6 class="mb-2">
                                <i class="fa-solid fa-clock me-2 text-warning"></i>
                                Opening hours
                            </h6>
                            <ul class="opening-hours list-unstyled small mb-3">
                                <li><span>Monday – Sunday</span><span>Open (hours may vary)</span></li>
                            </ul>

                            <h6 class="mb-2">
                                <i class="fa-solid fa-location-dot me-2 text-warning"></i>
                                Stall location
                            </h6>
                            <p class="small mb-3">
                                Blk 16 Lot 26 Laos St Corner Lebanon St<br>
                                San Marino City, Dasmariñas, Cavite, 4114
                            </p>

                            <div class="ratio ratio-4x3">
                                <!-- Placeholder map again -->
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1932.8790160741405!2d120.9786778244057!3d14.32548291342115!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d42dc98af931%3A0x7e9daa52ac95816b!2sSHAWARMA%20DEPOT!5e0!3m2!1sen!2sph!4v1763565541382!5m2!1sen!2sph"
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border small mb-0">
                        <i class="fa-solid fa-circle-info me-1 text-warning"></i>
                        For live order tracking or delivery concerns, use the
                        <a href="track-order.php">Track Order</a> page or contact us via Facebook
                        for the fastest response.
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

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
<script type="module" src="assets/js/main.js"></script>
</body>
</html>
