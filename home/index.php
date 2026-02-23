<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../public_bootstrap.php";
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JV Bohol Car Rental</title>
    <link rel="icon" href="../assets/images/jv_bohol_car_rental_logo.png" type="image/png">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap 5.3 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../assets/css/custom.css" rel="stylesheet">

    <style>
        .hero-bg {
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('https://lh3.googleusercontent.com/aida-public/AB6AXuChye4zWPzZHv2ysqwHW4t13S8gKhjqgQtOYZyp-3qwZDhOt9_3jJ0TQKTdnJbKMJycPDxstOo8V7q2mSKs8g4Al2XeevIR5p32Vq5rTanuCty9BpWZFrx6wZU50ZjMMwWVopeHCibqagJNeczo1YVlMmaiaPgkHQlUPtQPzaeCDVjDv8etoDYd7wp0cYrYsDBah-ORfVlyMkzzM-pExCjRuMx6KC78o3HTgBWuQ0D3aFnYprWVfx--TGNvlvZVNJLAsZG-xQj2KQlO');
            background-size: cover;
            background-position: center;
        }

        /* Specific overrides for Homepage map image blend */
        .map-blend {
            opacity: 0.3;
            mix-blend-mode: overlay;
            filter: grayscale(100%) contrast(150%);
        }
    </style>
</head>

<body class="font-display">

    <!-- Header Placeholder -->
    <div id="header-placeholder"></div>

    <!-- Hero Section -->
    <section class="position-relative overflow-hidden" style="padding-top: 5rem; padding-bottom: 4rem;">
        <div class="position-absolute top-0 start-0 w-100 h-100 z-0">
            <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuDiAE8uWUoZ-a0z6lpz54g6SGtRv5hHyPOdwg4HcS8cfYeIxFXKck--aVSW0AMPe5IEFP248B76eaEbMb_QdP21UaMFeHgGOfFFWf-GW2pCcAcrM3wCUMguk3ZOQkxWaLmSYozdgORg2P1cDpJwWHmGKwbbHRAlkjYYbsB-OBbP3ingw-K1uR5GNCtcZ35HfMZnaJ5tdjcz9ZjP6p1Btdx_OqXtkKqhzKugOot_e3mLZpQ0qNP8wjdUTRjOYJtAyqb0elcbaGjMc95u"
                alt="Scenic road in Bohol" class="w-100 h-100 object-fit-cover">
            <div class="position-absolute top-0 start-0 w-100 h-100 overlay-gradient"></div>
        </div>

        <div class="position-relative z-1 h-100 d-flex align-items-center">
            <div class="container-fluid container-xl">
                <div class="py-5" style="max-width: 42rem;">

                    <div class="d-inline-block fw-bold px-3 py-1 rounded-pill mb-4 text-uppercase tracking-wider animate-fade-up"
                        style="font-size: 0.75rem; background-color: rgba(209, 250, 229, 0.9); color: #065f46; border: 1px solid #a7f3d0;">
                        Trusted Self-Drive Service in Bohol
                    </div>

                    <h1 class="display-4 fw-bold text-white mb-4 lh-sm animate-fade-up animate-delay-100">
                        Explore Bohol Your Way: <span class="text-primary-homepage">Premium Self-Drive</span> Car
                        Rentals
                    </h1>

                    <p class="fs-5 text-light mb-5 lh-lg animate-fade-up animate-delay-200" style="max-width: 36rem;">
                        Experience the ultimate freedom of the open road. Choose from our well-maintained fleet of
                        sedans, SUVs, and vans for a safe, reliable, and unforgettable Bohol adventure.
                    </p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mb-5 animate-fade-up animate-delay-300">
                        <a href="#"
                            onclick="event.preventDefault(); window.open(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? 'https://m.me/jv.bohol.car.rental' : 'https://www.facebook.com/messages/t/jv.bohol.car.rental', '_blank');"
                            class="btn btn-primary d-flex align-items-center justify-content-center gap-2 rounded-pill fw-semibold px-4 py-3 shadow"
                            style="background-color: #0084ff; border: none;">
                            <i class="fab fa-facebook-messenger"></i> Chat on Messenger
                        </a>
                        <a href="#fleet"
                            class="btn btn-light d-flex align-items-center justify-content-center rounded-pill fw-semibold px-4 py-3 shadow text-dark">
                            View Fleet
                        </a>
                    </div>

                    <div class="d-flex flex-wrap gap-4 small fw-medium text-light">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle text-primary-homepage"></i> 24/7 Support
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <!-- Fleet Section -->
    <section id="fleet" class="py-5 py-md-5 transition bg-surface">
        <div class="container-fluid container-xl py-4">

            <div class="text-center mx-auto mb-5 pb-3 animate-fade-up" style="max-width: 48rem;">
                <h2 class="display-6 fw-bold mb-3">Why Choose JV Bohol?</h2>
                <p class="fs-5 text-muted-custom">We make car rental simple, transparent, and worry-free so you can
                    focus on your vacation. Choose the perfect vehicle from our premium fleet.</p>
            </div>

            <?php
            $categoryLabels = [
                'hatchbacks' => 'Hatchback',
                'sedans' => 'Sedan',
                'mpvs' => 'MPV',
                'pickup-trucks' => 'Pickup Truck',
                'vans' => 'Van'
            ];
            $allowedModels = ['Toyota Wigo 2019', 'Toyota Vios 2024', 'Toyota Innova 2024', 'Nissan Navara 2023'];
            $homeVehicles = [];
            foreach ($vehicles as $v) {
                if (in_array($v['model_name'], $allowedModels)) {
                    $homeVehicles[] = $v;
                }
            }
            // Ensure they appear in the exact order requested
            usort($homeVehicles, function ($a, $b) use ($allowedModels) {
                return array_search($a['model_name'], $allowedModels) - array_search($b['model_name'], $allowedModels);
            });
            ?>
            <div class="row g-4 justify-content-center">
                <?php foreach ($homeVehicles as $v): ?>
                    <div class="col-12 col-sm-6 col-lg-3 animate-fade-up animate-delay-200">
                        <div
                            class="card h-100 border border-surface bg-surface rounded-2xl shadow-sm overflow-hidden group">
                            <div class="position-relative overflow-hidden"
                                style="height: 12rem; background-color: #f3f4f6;">
                                <?php if ($v['badge_label']): ?>
                                    <span
                                        class="position-absolute top-0 end-0 m-3 z-1 badge bg-white text-dark shadow-sm px-2 py-1"><?= htmlspecialchars($v['badge_label']) ?></span>
                                <?php endif; ?>
                                <?php if ($v['image_path']): ?>
                                    <img src="<?= UPLOAD_URL . htmlspecialchars($v['image_path']) ?>"
                                        alt="<?= htmlspecialchars($v['model_name']) ?>"
                                        class="w-100 h-100 object-fit-cover hover-scale-img">
                                <?php else: ?>
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted-custom">
                                        <i class="fas fa-car fa-3x" style="opacity: 0.2;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-4">
                                <h3 class="h5 fw-bold mb-1"><?= htmlspecialchars($v['model_name']) ?></h3>
                                <div class="text-primary-homepage small fw-medium mb-3">
                                    <?= htmlspecialchars($categoryLabels[$v['category']] ?? $v['category']) ?>
                                </div>

                                <div class="d-flex align-items-end justify-content-between mt-auto">
                                    <div class="text-muted-custom" style="font-size: 0.75rem;">Starts at</div>
                                    <div class="fs-5 fw-bold text-primary-homepage">
                                        ₱<?= number_format((float) $v['price_per_day'], 0) ?><span
                                            class="fw-normal text-muted-custom" style="font-size: 0.75rem;">/day</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-5 text-center">
                <p class="text-muted-custom mb-4">Explore our full line-up of vehicles for your perfect Bohol trip.</p>
                <a href="../fleet/index.php"
                    class="btn btn-primary d-inline-flex align-items-center justify-content-center gap-2 rounded-pill fw-semibold px-5 py-3 shadow"
                    style="background-color: #00d632; border: none;">
                    View Full Fleet <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </section>

    <!-- Contact Block / Map -->
    <section id="contact" class="py-5 bg-surface transition px-3 px-md-5">
        <div class="container-fluid container-xl">
            <div class="rounded-3xl overflow-hidden shadow-lg" style="background-color: #1a1f2e;">
                <div class="row g-0">
                    <div
                        class="col-12 col-md-6 p-4 p-md-5 d-flex flex-column justify-content-center position-relative z-1">
                        <h2 class="display-6 fw-bold text-white mb-4">Ready to Explore Bohol?</h2>
                        <p class="fs-5 text-white text-opacity-75 mb-5" style="max-width: 28rem;">
                            Pick up your car at the airport, sea port, or have it delivered to your hotel. We make it
                            easy.
                        </p>

                        <div class="d-flex flex-column gap-4 mb-5">
                            <div class="d-flex align-items-start gap-3">
                                <div class="d-flex justify-content-center" style="width: 2rem;"><i
                                        class="fas fa-map-marker-alt text-primary-homepage fs-5"></i></div>
                                <div class="text-white text-opacity-75">Tagbilaran City, Bohol, Philippines</div>
                            </div>
                            <div class="d-flex align-items-center justify-content-start gap-3 flex-wrap">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex justify-content-center" style="width: 1.5rem;"><i
                                            class="fas fa-phone-alt text-primary-homepage"></i></div>
                                    <div class="text-white text-opacity-75">DITO: 0993-613-5108</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex justify-content-center" style="width: 1.5rem;"><i
                                            class="fab fa-whatsapp text-primary-homepage"></i></div>
                                    <div class="text-white text-opacity-75">WhatsApp</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex justify-content-center" style="width: 1.5rem;"><i
                                            class="fab fa-viber text-primary-homepage"></i></div>
                                    <div class="text-white text-opacity-75">Viber</div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex justify-content-center" style="width: 1.5rem;"><i
                                            class="fas fa-comment text-primary-homepage"></i></div>
                                    <div class="text-white text-opacity-75">KakaoTalk</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mt-2">
                                <div class="d-flex justify-content-center" style="width: 2rem;"><i
                                        class="fas fa-envelope text-primary-homepage"></i></div>
                                <div class="text-white text-opacity-75">jvboholcarrental@gmail.com</div>
                            </div>
                        </div>

                        <div>
                            <a href="../fleet/index.php"
                                class="btn btn-primary-custom fw-bold px-5 py-3 rounded-3 shadow">
                                Book Your Ride Now
                            </a>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 position-relative d-none d-md-block"
                        style="background-color: #1f2937; min-height: 400px;">
                        <iframe
                            src="https://maps.google.com/maps?q=Bohol,%20Philippines&t=&z=10&ie=UTF8&iwloc=&output=embed"
                            width="100%" height="100%" style="border:0; position: absolute; top: 0; left: 0;"
                            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-surface transition pt-5 pb-4 border-top border-surface">
        <div class="container-fluid container-xl">
            <div class="row g-4 mb-5">
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="../assets/images/jv_bohol_car_rental_logo.png" alt="JV Bohol Logo"
                            style="width: 2rem; height: 2rem; object-fit: contain;">
                        <span class="fw-bold fs-5 text-body">JV Bohol</span>
                    </div>
                    <p class="small text-muted-custom lh-lg mb-4">
                        Providing top-quality self-drive vehicles for tourists and locals in Bohol. Safe, reliable, and
                        affordable.
                    </p>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fw-bold mb-3 small text-uppercase tracking-wider">Quick Links</h4>
                    <ul class="list-unstyled d-flex flex-column gap-2 small text-muted-custom">
                        <li><a href="../home/index.php"
                                class="text-decoration-none text-muted-custom hover-primary">Home</a></li>
                        <li><a href="../fleet/index.php"
                                class="text-decoration-none text-muted-custom hover-primary">Fleet</a></li>
                        <li><a href="../about/index.html"
                                class="text-decoration-none text-muted-custom hover-primary">About Us</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fw-bold mb-3 small text-uppercase tracking-wider">Support</h4>
                    <ul class="list-unstyled d-flex flex-column gap-2 small text-muted-custom">
                        <li><a href="../contact/index.html"
                                class="text-decoration-none text-muted-custom hover-primary">Contact</a></li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">Terms &
                                Conditions</a></li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">Privacy Policy</a>
                        </li>
                        <li><a href="#" class="text-decoration-none text-muted-custom hover-primary">FAQs</a></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3">
                    <h4 class="fw-bold mb-3 small text-uppercase tracking-wider">Connect</h4>
                    <div class="d-flex gap-3">
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-surface"
                            style="width: 2.5rem; height: 2.5rem;">
                            <i class="fab fa-facebook-f text-muted-custom"></i>
                        </a>
                        <a href="#"
                            class="btn btn-outline-secondary rounded-circle d-flex align-items-center justify-content-center border-surface"
                            style="width: 2.5rem; height: 2.5rem;">
                            <i class="fab fa-instagram text-muted-custom"></i>
                        </a>
                    </div>
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