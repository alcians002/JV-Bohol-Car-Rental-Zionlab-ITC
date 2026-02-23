<?php
/**
 * Dashboard View — Bootstrap 5 Admin Interface (v2)
 * Expanded with category, color, seating, transmission, price, badge fields.
 *
 * Variables from VehicleController::handleList():
 *   $vehicles — array of vehicle rows
 *   $search   — current search term
 */

$msg = $_GET['msg'] ?? '';
$msgType = $_GET['msg_type'] ?? 'info';

$totalCount = count($vehicles);
$availCount = count(array_filter($vehicles, fn($v) => $v['status'] === 'Available'));
$maintCount = count(array_filter($vehicles, fn($v) => $v['status'] === 'Maintenance'));
$outCount = count(array_filter($vehicles, fn($v) => $v['status'] === 'Out'));

function badgeClass(string $status): string
{
    return match ($status) {
        'Available' => 'badge-available',
        'Maintenance' => 'badge-maintenance',
        'Out' => 'badge-out',
        default => 'bg-secondary',
    };
}

$categoryLabels = [
    'hatchbacks' => 'Hatchbacks',
    'sedans' => 'Sedans',
    'mpvs' => 'MPVs',
    'pickup-trucks' => 'Pickup Trucks',
    'vans' => 'Vans',
];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fleet Dashboard — JV Bohol Car Rental</title>
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

        <?php $currentPage = $_GET['page'] ?? 'fleet'; ?>
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
            &copy; <?= date('Y') ?> JV Bohol
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
                <h1 class="h3 fw-bold mb-1">Fleet Management</h1>
                <p class="mb-0 small" style="color: var(--admin-muted);">Manage your vehicle inventory in one place.</p>
            </div>
            <button class="btn btn-admin-accent d-flex align-items-center gap-2 rounded-pill px-4"
                data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus"></i> Add Vehicle
            </button>
        </div>

        <!-- Stat Cards -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-value" id="statTotal"><?= $totalCount ?></div>
                    <div class="stat-label">Total Vehicles</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-value" id="statAvailable" style="color: var(--admin-accent);"><?= $availCount ?>
                    </div>
                    <div class="stat-label">Available</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-value" id="statMaintenance" style="color: #ffc107;"><?= $maintCount ?></div>
                    <div class="stat-label">Maintenance</div>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-value" id="statOut" style="color: #dc3545;"><?= $outCount ?></div>
                    <div class="stat-label">Out / Rented</div>
                </div>
            </div>
        </div>

        <!-- Search -->
        <div
            class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <div class="search-bar position-relative flex-grow-1" style="max-width: 340px;">
                <i class="fas fa-search search-icon"></i>
                <input type="text" id="searchInput" class="form-control form-control-sm" placeholder="Search vehicles…"
                    value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="small" style="color: var(--admin-muted);">
                Showing <strong><?= $totalCount ?></strong> vehicle<?= $totalCount !== 1 ? 's' : '' ?>
            </div>
        </div>

        <!-- Data Table -->
        <div class="table-dark-admin">
            <div class="table-responsive">
                <table class="table table-borderless align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Model</th>
                            <th>Year</th>
                            <th>Category</th>
                            <th>Color</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Quick Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="fleetTableBody">
                        <?php if (empty($vehicles)): ?>
                            <tr>
                                <td colspan="9" class="text-center py-5" style="color: var(--admin-muted);">No vehicles
                                    found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($vehicles as $v): ?>
                                <tr>
                                    <td>
                                        <?php if ($v['image_path']): ?>
                                            <img src="uploads/<?= htmlspecialchars($v['image_path']) ?>"
                                                alt="<?= htmlspecialchars($v['model_name']) ?>" class="vehicle-thumb">
                                        <?php else: ?>
                                            <div class="vehicle-thumb-placeholder"><i class="fas fa-image"></i></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="fw-medium"><?= htmlspecialchars($v['model_name']) ?></td>
                                    <td><?= (int) $v['year'] ?></td>
                                    <td><span
                                            class="small"><?= htmlspecialchars($categoryLabels[$v['category']] ?? $v['category']) ?></span>
                                    </td>
                                    <td class="small"><?= htmlspecialchars($v['color']) ?></td>
                                    <td class="fw-medium">₱<?= number_format((float) $v['price_per_day'], 0) ?></td>
                                    <td>
                                        <span class="badge rounded-pill status-badge <?= badgeClass($v['status']) ?>">
                                            <?= htmlspecialchars($v['status']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm status-select" data-id="<?= (int) $v['id'] ?>"
                                            style="width: auto; min-width: 130px;">
                                            <option value="Available" <?= $v['status'] === 'Available' ? 'selected' : '' ?>>
                                                Available</option>
                                            <option value="Maintenance" <?= $v['status'] === 'Maintenance' ? 'selected' : '' ?>>
                                                Maintenance</option>
                                            <option value="Out" <?= $v['status'] === 'Out' ? 'selected' : '' ?>>Out</option>
                                        </select>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-1 justify-content-end">
                                            <button class="btn-action btn-edit" data-id="<?= (int) $v['id'] ?>" title="Edit">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                            <button class="btn-action delete btn-delete" data-id="<?= (int) $v['id'] ?>"
                                                data-name="<?= htmlspecialchars($v['model_name']) ?>" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <!-- ============================================================
     ADD VEHICLE MODAL (expanded)
     ============================================================ -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" action="index.php?action=create" method="POST" enctype="multipart/form-data">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Add Vehicle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Model Name -->
                        <div class="col-md-8">
                            <label class="form-label small fw-medium">Model Name</label>
                            <input type="text" name="model_name" class="form-control"
                                placeholder="e.g. Toyota Wigo 2026" required>
                        </div>
                        <!-- Year -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Year</label>
                            <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" min="2000"
                                max="2099" required>
                        </div>
                        <!-- Category -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Category</label>
                            <select name="category" class="form-select">
                                <?php foreach ($categoryLabels as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <!-- Color -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Color</label>
                            <input type="text" name="color" class="form-control" placeholder="e.g. Black">
                        </div>
                        <!-- Seating -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Seating</label>
                            <input type="text" name="seating" class="form-control" value="5 Seater"
                                placeholder="e.g. 5 Seater">
                        </div>
                        <!-- Transmission -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Transmission</label>
                            <select name="transmission" class="form-select">
                                <option value="Auto">Auto</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                        <!-- Price -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Price / Day (₱)</label>
                            <input type="number" name="price_per_day" class="form-control" value="1500" min="0"
                                step="100">
                        </div>
                        <!-- Badge -->
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Badge Label <span
                                    class="text-muted">(optional)</span></label>
                            <input type="text" name="badge_label" class="form-control" placeholder="e.g. Economy, 4x4">
                        </div>
                        <!-- Status -->
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Status</label>
                            <select name="status" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Out">Out</option>
                            </select>
                        </div>
                        <!-- Image -->
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Image <span class="text-muted">(JPG/PNG, max 2
                                    MB)</span></label>
                            <input type="file" name="image" class="form-control" accept=".jpg,.jpeg,.png">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-admin-accent rounded-pill px-4">Add Vehicle</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ============================================================
     EDIT VEHICLE MODAL (expanded)
     ============================================================ -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" action="index.php?action=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="editId">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold">Edit Vehicle</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label small fw-medium">Model Name</label>
                            <input type="text" name="model_name" id="editModelName" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Year</label>
                            <input type="number" name="year" id="editYear" class="form-control" min="2000" max="2099"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Category</label>
                            <select name="category" id="editCategory" class="form-select">
                                <?php foreach ($categoryLabels as $key => $label): ?>
                                    <option value="<?= $key ?>"><?= $label ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Color</label>
                            <input type="text" name="color" id="editColor" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Seating</label>
                            <input type="text" name="seating" id="editSeating" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Transmission</label>
                            <select name="transmission" id="editTransmission" class="form-select">
                                <option value="Auto">Auto</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Price / Day (₱)</label>
                            <input type="number" name="price_per_day" id="editPrice" class="form-control" min="0"
                                step="100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-medium">Badge Label</label>
                            <input type="text" name="badge_label" id="editBadge" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Status</label>
                            <select name="status" id="editStatus" class="form-select">
                                <option value="Available">Available</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Out">Out</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-medium">Replace Image <span
                                    class="text-muted">(optional)</span></label>
                            <input type="file" name="image" id="editImage" class="form-control"
                                accept=".jpg,.jpeg,.png">
                        </div>
                        <div class="col-12">
                            <img id="editImagePreview" src="" alt="Current" class="vehicle-thumb d-none"
                                style="width: 100px; height: 72px;">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill px-3"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-admin-accent rounded-pill px-4">Save Changes</button>
                </div>
            </form>
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