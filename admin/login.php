<?php
// Initialize session with secure params
if (session_status() === PHP_SESSION_NONE) {
    session_start([
        'cookie_httponly' => true,
        'cookie_secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on',
        'cookie_samesite' => 'Strict',
    ]);
}

require_once __DIR__ . '/models/Admin.php';

// Redirect if already logged in
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $adminModel = new Admin();
        if ($adminModel->verify($username, $password)) {
            // Prevent session fixation
            session_regenerate_id(true);
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Invalid username or password.';
        }
    } else {
        $error = 'Please enter both username and password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — JV Bohol Car Rental</title>
    <link rel="icon" href="../assets/images/jv_bohol_car_rental_logo.png" type="image/png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
    <style>
        body {
            background-color: var(--admin-bg);
            color: var(--admin-text);
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .login-card {
            background-color: var(--admin-surface);
            border: 1px solid var(--admin-border);
            border-radius: 1rem;
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.05);
            border-color: var(--admin-border);
            color: #fff;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.1);
            border-color: var(--admin-accent);
            color: #fff;
            box-shadow: none;
        }
    </style>
</head>

<body>
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="../assets/images/jv_bohol_car_rental_logo.png" alt="JV Bohol Logo"
                style="width: 3rem; height: 3rem; object-fit: contain; margin-bottom: 1rem;">
            <h2 class="h4 fw-bold mb-0">Admin Login</h2>
            <p class="small text-muted mt-1">Sign in to manage fleet and bookings</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger py-2 small fw-medium text-center" role="alert">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label small fw-medium text-light">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary text-muted"><i
                            class="fas fa-user"></i></span>
                    <input type="text" class="form-control border-start-0 ps-0" id="username" name="username" required
                        autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label for="password" class="form-label small fw-medium text-light">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-transparent border-end-0 border-secondary text-muted"><i
                            class="fas fa-lock"></i></span>
                    <input type="password" class="form-control border-start-0 ps-0" id="password" name="password"
                        required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary-custom w-100 fw-bold py-2 shadow-sm">
                Login
            </button>
        </form>
    </div>
</body>

</html>