<?php
/**
 * Bookings View — Placeholder UI
 */

$msg = $_GET['msg'] ?? '';
$msgType = $_GET['msg_type'] ?? 'info';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings — JV Bohol Car Rental Admin</title>
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

        <?php $currentPage = $_GET['page'] ?? 'bookings'; ?>
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
                <h1 class="h3 fw-bold mb-1">Bookings</h1>
                <p class="mb-0 small" style="color: var(--admin-muted);">Manage customer reservations and schedules.</p>
            </div>
            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto align-items-center">
                <form action="index.php" method="GET" class="d-flex gap-2">
                    <input type="hidden" name="page" value="bookings">
                    <div class="input-group input-group-sm search-bar position-relative" style="width: 300px;">
                        <i class="fas fa-search search-icon z-1 position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" name="q" id="searchInput" class="form-control bg-dark border-secondary text-white ps-5 w-100 rounded-pill" placeholder="Search customer, ref, or vehicle"
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                </form>
                <a href="index.php?page=bookings&action=export" class="btn btn-sm btn-outline-light">
                    <i class="fas fa-file-export"></i> Export
                </a>
                <button class="btn btn-sm btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                    <i class="fas fa-plus"></i> Add Booking
                </button>
            </div>
        </div>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-12 col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between">
                    <div>
                        <div class="stat-value text-white"><?= count($allBookings) ?></div>
                        <div class="stat-label">Total Bookings</div>
                    </div>
                    <div class="fs-1 text-white opacity-25">
                        <i class="fas fa-folder-open"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between border-warning border-opacity-50" style="background: rgba(255, 193, 7, 0.03);">
                    <div>
                        <div class="stat-value text-warning"><?= $pendingCount ?></div>
                        <div class="stat-label text-warning opacity-75">Action Required (Pending)</div>
                    </div>
                    <div class="fs-1 text-warning opacity-25">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="stat-card d-flex align-items-center justify-content-between" style="border-color: rgba(0, 214, 50, 0.3); background: rgba(0, 214, 50, 0.03);">
                    <div>
                        <div class="stat-value" style="color: var(--admin-accent);"><?= $activeCount ?></div>
                        <div class="stat-label" style="color: var(--admin-accent); opacity: 0.75;">Active Rentals</div>
                    </div>
                    <div class="fs-1" style="color: var(--admin-accent); opacity: 0.25;">
                        <i class="fas fa-car-side"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-dark-admin shadow-sm">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th style="width: 12%;">Ref #</th>
                            <th style="width: 25%;">Customer & Contact</th>
                            <th style="width: 25%;">Vehicle</th>
                            <th style="width: 18%;">Schedule</th>
                            <th style="width: 15%;">Status & Payment</th>
                            <th class="text-end" style="width: 5%;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <?php if (empty($bookings)): ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5" style="color: var(--admin-muted);">No bookings
                                        found.</td>
                                </tr>
                        <?php else: ?>
                                <?php foreach ($bookings as $b): ?>
                                        <tr>
                                            <td class="font-monospace">
                                                <span class="badge bg-secondary bg-opacity-25 text-light border border-secondary px-2 py-1"><?= htmlspecialchars($b['booking_ref']) ?></span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold text-white mb-1"><?= htmlspecialchars($b['customer_name']) ?></div>
                                                        <div class="small d-flex align-items-center gap-1" style="color: var(--admin-muted);">
                                                            <i class="fas fa-phone-alt" style="font-size: 0.7rem;"></i> <?= htmlspecialchars($b['customer_phone']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if ($b['image_path']): ?>
                                                            <img src="uploads/<?= htmlspecialchars($b['image_path']) ?>" alt=""
                                                                style="width: 64px; height: 44px; object-fit: cover; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                                    <?php else: ?>
                                                            <div
                                                                style="width: 64px; height: 44px; background: rgba(255,255,255,0.05); border-radius: 6px; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                                                <i class="fas fa-car text-muted opacity-50"></i>
                                                            </div>
                                                    <?php endif; ?>
                                                    <div>
                                                        <div class="fw-medium text-light mb-1"><?= htmlspecialchars($b['model_name']) ?></div>
                                                        <div class="badge bg-dark border border-secondary text-secondary" style="font-size: 0.70rem; font-weight: 500;">
                                                            <?= htmlspecialchars($b['category']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-1">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="width: 35px;">OUT</span>
                                                        <span class="small text-light form-control-plaintext py-0"><?= date('M j, Y g:i A', strtotime($b['pickup_date'])) ?></span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25" style="width: 35px;">IN</span>
                                                        <span class="small text-light form-control-plaintext py-0"><?= date('M j, Y g:i A', strtotime($b['return_date'])) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column gap-2">
                                                    <select class="form-select form-select-sm status-select bg-dark text-white border-secondary"
                                                        data-id="<?= (int) $b['id'] ?>" style="border-radius: 0.5rem; font-weight: 500;">
                                                        <option value="Pending" <?= $b['booking_status'] === 'Pending' ? 'selected' : '' ?>>🕒 Pending</option>
                                                        <option value="Confirmed" <?= $b['booking_status'] === 'Confirmed' ? 'selected' : '' ?>>✅ Confirmed</option>
                                                        <option value="Active" <?= $b['booking_status'] === 'Active' ? 'selected' : '' ?>>🚗 Active (Out)</option>
                                                        <option value="Completed" <?= $b['booking_status'] === 'Completed' ? 'selected' : '' ?>>🏁 Completed</option>
                                                        <option value="Cancelled" <?= $b['booking_status'] === 'Cancelled' ? 'selected' : '' ?>>❌ Cancelled</option>
                                                    </select>
                                            
                                                    <?php
                                                    // Dynamic Payment Badge Colors
                                                    $payColor = 'secondary';
                                                    if ($b['payment_status'] === 'Fully Paid')
                                                        $payColor = 'success';
                                                    if ($b['payment_status'] === 'Deposit Paid')
                                                        $payColor = 'info';
                                                    if ($b['payment_status'] === 'Unpaid')
                                                        $payColor = 'danger';
                                                    ?>
                                                    <div class="d-flex align-items-center justify-content-between px-1 mt-1">
                                                        <select class="form-select form-select-sm payment-select bg-<?= $payColor ?> bg-opacity-25 text-<?= $payColor ?> border border-<?= $payColor ?>"
                                                            data-id="<?= (int) $b['id'] ?>" style="border-radius: 0.5rem; font-weight: 600; font-size: 0.75rem; width: auto; min-width: 100px; padding: 0.1rem 1.25rem 0.1rem 0.5rem;">
                                                            <option value="Unpaid" <?= $b['payment_status'] === 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                                            <option value="Deposit Paid" <?= $b['payment_status'] === 'Deposit Paid' ? 'selected' : '' ?>>Deposit Paid</option>
                                                            <option value="Fully Paid" <?= $b['payment_status'] === 'Fully Paid' ? 'selected' : '' ?>>Fully Paid</option>
                                                        </select>
                                                        <span class="small fw-bold text-success ms-2">₱<?= number_format((float) $b['total_price'], 0) ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <form action="index.php?page=bookings&action=delete" method="POST" class="d-inline"
                                                    onsubmit="return confirm('Are you sure you want to completely delete this booking? This is irreversible.');">
                                                    <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                                    <button type="submit"
                                                        class="btn-action delete btn-delete bg-transparent border-0 text-danger p-2 fs-5"
                                                        title="Delete Booking">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- Add Booking Modal -->
    <div class="modal fade admin-modal" id="addBookingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-bottom border-secondary">
                    <h5 class="modal-title fw-bold">Add New Booking</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="index.php?page=bookings&action=create" method="POST">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small">Customer Name *</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary"
                                    name="customer_name" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Customer Phone</label>
                                <input type="text" class="form-control bg-dark text-white border-secondary"
                                    name="customer_phone">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Customer Email</label>
                                <input type="email" class="form-control bg-dark text-white border-secondary"
                                    name="customer_email">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Vehicle *</label>
                                <select class="form-select bg-dark text-white border-secondary" name="vehicle_id"
                                    required>
                                    <option value="" disabled selected>Select a vehicle...</option>
                                    <?php foreach ($vehicles as $v): ?>
                                            <option value="<?= $v['id'] ?>"><?= htmlspecialchars($v['model_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Pickup Date & Time *</label>
                                <input type="datetime-local" class="form-control bg-dark text-white border-secondary"
                                    name="pickup_date" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small">Return Date & Time *</label>
                                <input type="datetime-local" class="form-control bg-dark text-white border-secondary"
                                    name="return_date" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Total Price *</label>
                                <input type="number" step="0.01"
                                    class="form-control bg-dark text-white border-secondary" name="total_price"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Booking Status</label>
                                <select class="form-select bg-dark text-white border-secondary" name="booking_status">
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Active">Active</option>
                                    <option value="Completed">Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Payment Status</label>
                                <select class="form-select bg-dark text-white border-secondary" name="payment_status">
                                    <option value="Unpaid">Unpaid</option>
                                    <option value="Deposit Paid">Deposit Paid</option>
                                    <option value="Fully Paid">Fully Paid</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small">Notes</label>
                                <textarea class="form-control bg-dark text-white border-secondary" name="notes"
                                    rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-top border-secondary">
                        <button type="button" class="btn btn-outline-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary-custom">Save Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Dark Mode Toggle Button (Floating) -->
    <button class="admin-dark-toggle" title="Toggle Dark/Light Mode">
        <span class="material-icons-round light-icon" style="display: none;">light_mode</span>
        <span class="material-icons-round dark-icon">dark_mode</span>
    </button>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/admin.js"></script>

</body>

</html>