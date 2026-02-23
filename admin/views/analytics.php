<?php
/**
 * Analytics View — Placeholder UI
 */

$msg = $_GET['msg'] ?? '';
$msgType = $_GET['msg_type'] ?? 'info';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics — JV Bohol Car Rental Admin</title>
    <link rel="icon" href="../assets/images/jv_bohol_car_rental_logo.png" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="admin-sidebar d-flex flex-column p-3" id="adminSidebar">
        <div class="d-flex align-items-center gap-2 mb-4 px-1">
            <img src="../assets/images/jv_bohol_car_rental_logo.png" alt="JV Bohol Logo"
                style="width: 2.25rem; height: 2.25rem; object-fit: contain;">
            <span class="fw-bold" style="font-size: 0.95rem;">JV Bohol <span
                    style="color: var(--admin-accent); font-weight: 400;">Admin</span></span>
        </div>

        <?php $currentPage = $_GET['page'] ?? 'analytics'; ?>
        <nav class="nav flex-column gap-1">
            <a class="nav-link <?= $currentPage === 'fleet' ? 'active' : '' ?> d-flex align-items-center gap-2"
                href="index.php?page=fleet">
                <i class="fas fa-car"></i> Fleet
            </a>
            <a class="nav-link <?= $currentPage === 'bookings' ? 'active' : '' ?> d-flex align-items-center gap-2"
                href="index.php?page=bookings">
                <i class="fas fa-calendar-check"></i> Bookings
            </a>
            <a class="nav-link <?= $currentPage === 'analytics' ? 'active' : '' ?> d-flex align-items-center gap-2"
                href="index.php?page=analytics">
                <i class="fas fa-chart-bar"></i> Analytics
            </a>
            <a class="nav-link <?= $currentPage === 'settings' ? 'active' : '' ?> d-flex align-items-center gap-2"
                href="index.php?page=settings">
                <i class="fas fa-cog"></i> Settings
            </a>
            <hr class="border-secondary opacity-25 my-2">
            <a class="nav-link text-danger d-flex align-items-center gap-2 hover-danger" href="logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </nav>

        <div class="mt-auto small px-1" style="color: var(--admin-muted);">
            &copy;
            <?= date('Y') ?> JV Bohol
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="admin-main">

        <button class="btn btn-sm btn-outline-light d-lg-none mb-3" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>

        <?php if ($msg): ?>
            <div class="flash-toast alert alert-<?= htmlspecialchars($msgType) ?> alert-dismissible fade show shadow">
                <?= htmlspecialchars($msg) ?>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Page Header -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="h3 fw-bold mb-1">Analytics Dashboard</h1>
                <p class="mb-0 small" style="color: var(--admin-muted);">View insights into performance and fleet
                    utilization.</p>
            </div>
        </div>

        <!-- Placeholder Content -->
        <div class="card bg-dark border-0 rounded-4 shadow-sm h-100 d-flex align-items-center justify-content-center p-5 text-center"
            style="min-height: 400px; border: 1px dashed rgba(255,255,255,0.1) !important;">
            <div>
                <div class="text-white-50 mb-3">
                    <i class="fas fa-chart-pie fa-3x"></i>
                </div>
                <h3 class="h5 fw-bold text-white mb-2">Analytics Module Coming Soon</h3>
                <p class="text-muted mb-0 small" style="max-width: 320px; margin: 0 auto;">
                    This section will provide charts, metrics, and data exports to monitor your business growth and
                    vehicle popularity.
                </p>
            </div>
        </div>

    </main>

    <!-- Dark Mode Toggle Button (Floating) -->
    <button class="admin-dark-toggle" title="Toggle Dark/Light Mode">
        <span class="material-icons-round light-icon" style="display: none;">light_mode</span>
        <span class="material-icons-round dark-icon">dark_mode</span>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>

</body>

</html>