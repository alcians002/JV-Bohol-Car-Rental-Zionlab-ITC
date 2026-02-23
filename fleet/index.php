<?php require_once __DIR__ . "/../public_bootstrap.php"; ?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Fleet - JV Bohol Car Rental</title>
    <meta name="description"
        content="Premium self-drive car rentals in Bohol. Choose from our well-maintained fleet of cars, SUVs, and vans.">
    <link rel="icon" href="../assets/images/jv_bohol_car_rental_logo.png" type="image/png">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/custom.css" rel="stylesheet">

    <style>
        .hero-gradient {
            background-color: transparent;
            background-image: radial-gradient(circle at center, rgba(46, 204, 64, 0.05) 0%, transparent 60%);
        }

        [data-bs-theme="dark"] .hero-gradient {
            background-image: radial-gradient(circle at center, rgba(46, 204, 64, 0.03) 0%, transparent 60%);
        }
    </style>
</head>

<body class="font-display">

    <!-- Header Placeholder -->
    <div id="header-placeholder"></div>

    <main>
        <!-- Hero Section & Filters -->
        <section class="position-relative pb-5 overflow-hidden border-baseline border-bottom border-surface"
            style="padding-top: 8rem;">
            <!-- Background Decoration Removed as Global Body Mesh Gradient Handles It -->

            <div class="container-fluid container-xl position-relative z-1 text-center py-5">
                <div class="d-inline-flex align-items-center px-3 py-1 rounded-pill mb-4 border border-success"
                    style="background-color: rgba(220, 252, 231, 0.4); color: var(--primary-alt2); border-color: #86efac;">
                    <span class="text-uppercase fw-semibold tracking-wider" style="font-size: 0.75rem;">Premium
                        Self-Drive Service</span>
                </div>

                <h1 class="display-3 fw-bold mb-4 tracking-tight text-body">
                    Our Full <span class="text-primary-fleet">Fleet</span>
                </h1>

                <p class="fs-5 text-muted-custom mx-auto lh-lg mb-5 pb-3" style="max-width: 42rem;">
                    From compact cars to spacious vans, find the perfect ride for your Bohol trip. All vehicles are
                    well-maintained, insured, and ready for adventure.
                </p>

                <!-- Category Filters -->
                <div class="d-flex flex-wrap justify-content-center gap-3 w-100 mb-3">
                    <button class="filter-btn vehicle-filter btn btn-dark rounded-pill px-5 py-2 fw-medium border-0"
                        data-filter="all" aria-pressed="true">All</button>
                    <button
                        class="filter-btn vehicle-filter btn btn-outline-secondary btn-surface text-body rounded-pill px-5 py-2 fw-medium border-surface border bg-surface"
                        data-filter="hatchbacks" aria-pressed="false">Hatchback</button>
                    <button
                        class="filter-btn vehicle-filter btn btn-outline-secondary btn-surface text-body rounded-pill px-5 py-2 fw-medium border-surface border bg-surface"
                        data-filter="sedans" aria-pressed="false">Sedans</button>
                    <button
                        class="filter-btn vehicle-filter btn btn-outline-secondary btn-surface text-body rounded-pill px-5 py-2 fw-medium border-surface border bg-surface"
                        data-filter="mpvs" aria-pressed="false">MPV</button>
                    <button
                        class="filter-btn vehicle-filter btn btn-outline-secondary btn-surface text-body rounded-pill px-5 py-2 fw-medium border-surface border bg-surface"
                        data-filter="pickup-trucks" aria-pressed="false">Pickup Truck</button>
                    <button
                        class="filter-btn vehicle-filter btn btn-outline-secondary btn-surface text-body rounded-pill px-5 py-2 fw-medium border-surface border bg-surface"
                        data-filter="vans" aria-pressed="false">Van</button>
                </div>

                <!-- Sort Control -->
                <div class="d-flex justify-content-center">
                    <select id="sort-year"
                        class="form-select form-select-sm rounded-pill border-surface bg-surface text-body fw-medium"
                        style="width: auto; min-width: 10rem;">
                        <option value="">Sort by Year</option>
                        <option value="asc">Year ↑ Oldest First</option>
                        <option value="desc">Year ↓ Newest First</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Product Grid -->
        <section class="py-5 bg-surface transition">
            <div class="container-fluid container-xl py-4">
                <div class="row g-4 justify-content-center" id="fleet-grid">
                    <?php foreach ($vehicles as $v):
                        $statusLower = strtolower($v['status'] ?? 'available');
                        $badgeClass = $statusLower === 'available' ? 'badge-available' : ($statusLower === 'maintenance' ? 'badge-maintenance' : 'badge-out');
                        ?>
                        <div class="col-12 col-md-6 col-lg-4 col-xl-3 animate-fade-up animate-delay-200">
                            <div
                                class="card bg-surface h-100 border border-surface rounded-2xl shadow-sm overflow-hidden fleet-card">
                                <div class="position-relative overflow-hidden"
                                    style="height: 12rem; background-color: #f3f4f6;">
                                    <!-- Status Badge -->
                                    <span
                                        class="position-absolute top-0 start-0 m-3 z-1 badge <?= $badgeClass ?> small rounded-pill px-2 py-1 fw-semibold"><?= htmlspecialchars($v['status'] ?? 'Available') ?></span>
                                    <!-- Feature Badge -->
                                    <?php if ($v['badge_label']): ?>
                                        <span
                                            class="position-absolute top-0 end-0 m-3 z-1 badge bg-white text-dark shadow-sm px-2 py-1 fw-semibold small"><?= htmlspecialchars($v['badge_label']) ?></span>
                                    <?php endif; ?>
                                    <!-- Year Badge -->
                                    <span
                                        class="position-absolute bottom-0 start-0 m-3 z-1 badge bg-dark bg-opacity-75 text-white small rounded-pill px-2 py-1"><?= htmlspecialchars($v['year']) ?></span>
                                    <?php if ($v['image_path']): ?>
                                        <img src="<?= UPLOAD_URL . htmlspecialchars($v['image_path']) ?>"
                                            alt="<?= htmlspecialchars($v['model_name']) ?>"
                                            class="w-100 h-100 object-fit-cover hover-scale-img">
                                    <?php else: ?>
                                        <div
                                            class="w-100 h-100 d-flex align-items-center justify-content-center text-muted-custom">
                                            <i class="fas fa-car fa-3x" style="opacity: 0.2;"></i>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div data-category="<?= htmlspecialchars($v['category']) ?>"
                                    data-year="<?= htmlspecialchars($v['year']) ?>"
                                    data-status="<?= htmlspecialchars($statusLower) ?>" class="card-body p-4 vehicle-card">
                                    <h3 class="h5 fw-bold mb-1"><?= htmlspecialchars($v['model_name']) ?></h3>
                                    <div class="text-primary-fleet small fw-medium mb-3">
                                        <?= htmlspecialchars($v['color']) ?>
                                    </div>

                                    <div class="d-flex flex-wrap gap-2 mb-4 text-muted-custom" style="font-size: 0.75rem;">
                                        <span
                                            class="d-flex align-items-center gap-1 rounded bg-light border border-surface px-2 py-1"><i
                                                class="fas fa-user-friends small text-muted-custom"></i>
                                            <?= htmlspecialchars($v['seating']) ?></span>
                                        <span
                                            class="d-flex align-items-center gap-1 rounded bg-light border border-surface px-2 py-1"><i
                                                class="fas fa-cogs small text-muted-custom"></i>
                                            <?= htmlspecialchars($v['transmission']) ?></span>
                                    </div>

                                    <div class="d-flex align-items-end justify-content-between mb-4 mt-auto">
                                        <div class="text-muted-custom small">Starts at</div>
                                        <div class="fs-5 fw-bold text-primary-fleet">
                                            ₱<?= number_format((float) $v['price_per_day'], 0) ?><span
                                                class="fw-normal text-muted-custom small">/day</span></div>
                                    </div>

                                    <div class="d-flex gap-2">
                                        <a href="../contact/index.html?vehicle_id=<?= (int) $v['id'] ?>&vehicle=<?= urlencode($v['model_name']) ?>"
                                            class="btn btn-primary-custom w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2 py-2"
                                            style="background-color: var(--primary-alt2);">
                                            <span class="material-icons-round" style="font-size: 1.1rem;">chat</span>
                                            Inquire
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-5 bg-surface transition border-top border-surface">
            <div class="container-fluid container-xl py-5 text-center">
                <div class="mx-auto" style="max-width: 42rem;">
                    <h2 class="h3 fw-bold mb-3 tracking-tight">Can't find what you need?</h2>
                    <p class="text-muted-custom mb-5 px-3">We offer custom rental packages for long-term stays,
                        weddings, and corporate events. Message us for custom requests!</p>

                    <a href="#"
                        onclick="event.preventDefault(); window.open(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'https://m.me/jv.bohol.car.rental' : 'https://www.facebook.com/messages/t/jv.bohol.car.rental', '_blank');"
                        class="btn btn-primary d-inline-flex align-items-center gap-2 rounded-pill fw-semibold px-4 py-3 shadow"
                        style="background-color: #2563eb; border: none;">
                        <span class="material-icons-round fs-5">chat</span> Chat on Messenger
                    </a>

                    <div class="d-flex flex-wrap justify-content-center gap-4 mt-4 small fw-medium text-muted-custom">
                        <div class="d-flex align-items-center gap-2">
                            <span class="material-icons-round text-primary-fleet fs-6">headset_mic</span> 24/7 Support
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-surface transition pt-5 pb-4 border-top border-surface">
        <div class="container-fluid container-xl">
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-4 col-lg-3 pe-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="../assets/images/jv_bohol_car_rental_logo.png" alt="JV Bohol Logo"
                            style="width: 2rem; height: 2rem; object-fit: contain;">
                        <span class="fw-bold fs-5 text-body tracking-tight">JV Bohol</span>
                    </div>
                    <p class="small text-muted-custom lh-lg mb-4">
                        Providing top-quality self-drive vehicles for tourists and locals in Bohol. Safe, reliable, and
                        affordable car rentals for your island adventure.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-surface"
                            style="width: 2.25rem; height: 2.25rem;">
                            <!-- SVG for Facebook -->
                            <svg class="w-4 h-4 text-secondary-custom fill-current" viewBox="0 0 24 24"
                                style="width: 1rem; height: 1rem;">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-surface"
                            style="width: 2.25rem; height: 2.25rem;">
                            <!-- SVG for Instagram -->
                            <svg class="w-4 h-4 text-secondary-custom fill-current" viewBox="0 0 24 24"
                                style="width: 1rem; height: 1rem;">
                                <path
                                    d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <h4 class="fw-bold mb-4 small text-uppercase tracking-wider text-body">Quick Links</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3 small text-muted-custom">
                        <li><a href="../home/index.php"
                                class="text-decoration-none text-muted-custom hover-primary">Home</a></li>
                        <li><a href="../fleet/index.php"
                                class="text-decoration-none text-muted-custom hover-primary">Fleet</a></li>
                        <li><a href="../about/index.html"
                                class="text-decoration-none text-muted-custom hover-primary">About Us</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <h4 class="fw-bold mb-4 small text-uppercase tracking-wider text-body">Support</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3 small text-muted-custom">
                        <li><a href="../contact/index.html"
                                class="text-decoration-none text-muted-custom hover-primary">Contact</a></li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">Terms &
                                Conditions</a></li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">Privacy Policy</a>
                        </li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">FAQs</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-12 col-lg-3">
                    <h4 class="fw-bold mb-4 small text-uppercase tracking-wider text-body">Contact Us</h4>
                    <ul class="list-unstyled d-flex flex-column gap-3 small text-muted-custom">
                        <li class="d-flex align-items-start gap-2">
                            <span class="material-icons-round text-primary-fleet"
                                style="font-size: 1.125rem;">location_on</span>
                            <span>Tagbilaran City, Bohol, Philippines</span>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <span class="material-icons-round text-primary-fleet"
                                style="font-size: 1.125rem;">phone</span>
                            <span>DITO: 0993-613-5108</span>
                        </li>
                        <li class="d-flex flex-wrap gap-3 mt-1 mb-2">
                            <div class="d-flex align-items-center gap-1">
                                <i class="fab fa-whatsapp text-primary-fleet" style="font-size: 1.125rem;"></i>
                                <span class="small">WhatsApp</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fab fa-viber text-primary-fleet" style="font-size: 1.125rem;"></i>
                                <span class="small">Viber</span>
                            </div>
                            <div class="d-flex align-items-center gap-1">
                                <i class="fas fa-comment text-primary-fleet" style="font-size: 1.125rem;"></i>
                                <span class="small">KakaoTalk</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-2">
                            <span class="material-icons-round text-primary-fleet"
                                style="font-size: 1.125rem;">email</span>
                            <span>jvboholcarrental@gmail.com</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-md-center pt-4 border-top border-surface gap-3">
                <p class="small text-muted-custom mb-0">
                    © 2026 JV Bohol Car Rental. All rights reserved.
                </p>
                <div class="d-flex gap-4 small text-muted-custom">
                    <a href="#" class="text-decoration-none text-muted-custom hover-primary">Privacy</a>
                    <a href="#" class="text-decoration-none text-muted-custom hover-primary">Terms</a>
                    <a href="#" class="text-decoration-none text-muted-custom hover-primary">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Dark Mode Toggle Button (Floating) -->
    <div class="position-fixed bottom-0 end-0 p-4" style="z-index: 1050;">
        <button
            class="btn btn-dark rounded-circle shadow-lg d-flex align-items-center justify-content-center dark-mode-toggle"
            style="width: 3rem; height: 3rem;">
            <span class="material-icons-round light-icon" style="display: none;">light_mode</span>
            <span class="material-icons-round dark-icon">dark_mode</span>
        </button>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/filters.js"></script>
</body>

</html>