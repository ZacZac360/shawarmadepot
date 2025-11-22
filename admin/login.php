<?php
// admin/login.php - Admin Login

session_start();

// If already logged in, go straight to dashboard
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
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

$error_message = "";

// ---------------------- Handle login POST ----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error_message = "Please enter both username and password.";
    } else {
        $stmt = $mysqli->prepare("
            SELECT id, username, password_hash, full_name, role
            FROM admin_users
            WHERE username = ?
            LIMIT 1
        ");

        if ($stmt) {
            $stmt->bind_param("s", $username);
            if ($stmt->execute()) {
                $result = $stmt->get_result();
                $admin  = $result->fetch_assoc();
            }
            $stmt->close();
        }

        if (empty($admin)) {
            $error_message = "Invalid username or password.";
        } else {
            $stored_hash = $admin['password_hash'];

            // Only allow proper password_hash() values
            $isValid = password_verify($password, $stored_hash);

            if ($isValid) {
                // Set session
                $_SESSION['admin_id']       = (int)$admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['admin_name']     = $admin['full_name'];
                $_SESSION['admin_role']     = $admin['role'];

                header("Location: dashboard.php");
                exit;
            } else {
                $error_message = "Invalid username or password.";
            }
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Admin Login | Shawarma Depot</title>
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
<body class="bg-light d-flex flex-column min-vh-100">

<!-- ADMIN NAVBAR (minimal, but consistent) -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="../index.php">
            <img src="../assets/images/logo.png" alt="Shawarma Depot Logo" width="40" height="40">
            <div class="ms-2 d-flex flex-column lh-1">
                <strong>Shawarma Depot</strong>
                <span class="small text-warning">Admin Panel Login</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNavbar">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">
                        <i class="fa-solid fa-globe me-1"></i> View Site
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4 flex-grow-1">
    <section class="py-4">
        <div class="container" style="max-width: 520px;">
            <!-- Admin indicator -->
            <div class="alert alert-dark d-flex align-items-center small mb-4">
                <i class="fa-solid fa-user-shield me-2"></i>
                <div>
                    <strong>Admin Area:</strong>
                    <span class="text-muted">
                        Authorized staff only. Changes made after login affect live customer orders.
                    </span>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h1 class="h4 mb-1 text-center">
                        <i class="fa-solid fa-right-to-bracket me-2 text-warning"></i>
                        Admin Login
                    </h1>
                    <p class="text-muted small text-center mb-4">
                        Sign in with your administrator account to manage orders and settings.
                    </p>

                    <?php if ($error_message): ?>
                        <div class="alert alert-danger py-2 small">
                            <i class="fa-solid fa-circle-exclamation me-1"></i>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="login.php" autocomplete="off">
                        <div class="mb-3">
                            <label for="username" class="form-label small text-uppercase text-muted">
                                Username
                            </label>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   required
                                   autofocus
                                   placeholder="Enter admin username">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label small text-uppercase text-muted">
                                Password
                            </label>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   required
                                   placeholder="Enter your password">
                        </div>

                        <div class="text-center mt-4">
                            <small class="text-muted d-block mb-2">
                                Keep this login page private. Do not share your credentials.
                            </small>

                            <button type="submit"
                                    class="btn btn-warning fw-semibold text-dark px-4">
                                <i class="fa-solid fa-right-to-bracket me-1"></i>
                                Sign In
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <p class="text-center small text-muted mt-3 mb-0">
                If you’ve forgotten your credentials, contact the site owner.
            </p>
        </div>
    </section>
</main>

<!-- BACK TO TOP (if you keep this globally) -->
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
</body>
</html>
                    