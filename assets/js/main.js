/**
 * JV Bohol Car Rental - Main JavaScript
 * Vanilla JS rewriting of interactions.
 */

document.addEventListener('DOMContentLoaded', () => {

    // 1. Dark Mode Toggle Logic
    const initDarkMode = () => {
        const toggleBtns = document.querySelectorAll('.dark-mode-toggle');
        const htmlElement = document.documentElement;

        // Check local storage or system preference
        const savedTheme = localStorage.getItem('theme');
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
            htmlElement.setAttribute('data-bs-theme', 'dark');
        } else {
            htmlElement.setAttribute('data-bs-theme', 'light');
        }

        const updateIcons = (theme) => {
            toggleBtns.forEach(btn => {
                const darkIcon = btn.querySelector('.dark-icon');
                const lightIcon = btn.querySelector('.light-icon');
                if (darkIcon && lightIcon) {
                    if (theme === 'dark') {
                        darkIcon.style.display = 'none';
                        lightIcon.style.display = 'block';
                    } else {
                        darkIcon.style.display = 'block';
                        lightIcon.style.display = 'none';
                    }
                }
            });
        };

        updateIcons(htmlElement.getAttribute('data-bs-theme'));

        // Attach listeners
        toggleBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const currentTheme = htmlElement.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

                htmlElement.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateIcons(newTheme);
            });
        });
    };
    initDarkMode();

    // 2. Mobile Menu Toggle (if implemented via JS)
    // The original templates handled mobile menu via hidden classes that weren't fully hooked up.
    // We add a simple listener here if a nav id is present.
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const mobileNav = document.getElementById('mobile-nav'); // Will need to define this in HTML
    if (mobileMenuBtn && mobileNav) {
        mobileMenuBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('d-none');
        });
    }

    // 3. Fleet Category Filtering (Moved to filters.js)


    // 5. Shared Header Navigation Active State
    const initNavActiveState = () => {
        const navLinks = document.querySelectorAll('header nav a');
        if (!navLinks.length) return;

        const currentPath = window.location.pathname;
        let colorProfile = 'homepage';
        if (currentPath.includes('about') || currentPath.includes('rates')) colorProfile = 'about';
        if (currentPath.includes('fleet')) colorProfile = 'fleet';
        if (currentPath.includes('contact')) colorProfile = 'contact';

        navLinks.forEach(link => {
            const href = link.getAttribute('href');
            const pathParts = currentPath.split('/');
            const currentFolder = pathParts[pathParts.length - 2];

            if (href && href.includes(currentFolder)) {
                link.classList.remove('text-body', 'text-opacity-75', `text-primary-${colorProfile}-hover`);
                link.classList.add(`text-primary-${colorProfile}`, 'active');
            } else {
                link.classList.add('text-body', 'text-opacity-75', `text-primary-${colorProfile}-hover`);
                link.classList.remove(`text-primary-${colorProfile}`, 'active');
            }
        });
    };
    initNavActiveState();

    // 6. Header Partial Loader
    const headerPlaceholder = document.getElementById('header-placeholder');
    if (headerPlaceholder) {
        // Map path sections to their original color classes for seamless integration.
        // Even though the DOM is unified, we apply the parent's color theme to the header for stylistic consistency.
        const currentPath = window.location.pathname;
        let colorProfile = 'homepage';
        if (currentPath.includes('about') || currentPath.includes('rates')) colorProfile = 'about';
        if (currentPath.includes('fleet')) colorProfile = 'fleet';
        if (currentPath.includes('contact')) colorProfile = 'contact';

        let iconProfile = 'material-icons-round';
        if (colorProfile === 'contact') iconProfile = 'material-icons-outlined';

        const fontClass = (currentPath.includes('home') || currentPath.includes('contact')) ? 'font-display' : '';
        const fontWeightClass = (colorProfile === 'homepage' || colorProfile === 'contact') ? 'fw-bold' : 'fw-medium';

        const headerHTML = `
<!-- Header Structure (Shared) -->
<header class="fixed-top header-glass border-bottom border-surface transition ${fontClass}">
    <div class="container-fluid container-xl">
        <div class="d-flex justify-content-between align-items-center" style="height: 5rem;">

            <!-- Logo -->
            <div class="d-flex align-items-center gap-2 cursor-pointer">
                <img src="../assets/images/jv_bohol_car_rental_logo.png" alt="JV Bohol Logo" style="width: 2.5rem; height: 2.5rem; object-fit: contain;">
                <span class="fw-bold fs-5 tracking-tight text-body">JV Bohol <span
                        class="text-primary-${colorProfile} fw-normal">Car Rental</span></span>
            </div>

            <!-- Desktop Nav -->
            <nav class="d-none d-md-flex gap-4">
                <a href="../home/index.php"
                    class="text-decoration-none text-body text-opacity-75 text-primary-${colorProfile}-hover ${fontWeightClass} transition">Home</a>
                <a href="../fleet/index.php"
                    class="text-decoration-none text-body text-opacity-75 text-primary-${colorProfile}-hover ${fontWeightClass} transition">Fleet</a>
                <a href="../about/index.html"
                    class="text-decoration-none text-body text-opacity-75 text-primary-${colorProfile}-hover ${fontWeightClass} transition">About Us</a>
                <a href="../contact/index.html"
                    class="text-decoration-none text-body text-opacity-75 text-primary-${colorProfile}-hover ${fontWeightClass} transition">Contact</a>
            </nav>

            <!-- CTA -->
            <div class="d-none d-md-flex align-items-center">
                <a href="../contact/index.html#contact"
                    class="btn btn-primary-custom rounded-pill fw-semibold px-4 py-2 d-flex align-items-center gap-2 shadow"
                    style="background-color: var(--primary${colorProfile === 'homepage' ? '' : (colorProfile === 'about' ? '-alt1' : (colorProfile === 'fleet' ? '-alt2' : '-alt3'))});">
                    Book Now <span class="${iconProfile} fs-6 ms-1">arrow_forward</span>
                </a>
            </div>

            <!-- Mobile Menu Toggle Button -->
            <div class="d-flex d-md-none align-items-center">
                <button class="btn btn-link text-body p-0 mobile-menu-btn" aria-label="Open mobile menu">
                    <span class="${iconProfile} fs-1">menu</span>
                </button>
            </div>
        </div>
    </div>
</header>

<!-- Mobile Overlay Navigation -->
<div class="mobile-menu-overlay d-md-none" id="mobileMenuOverlay">
    <div class="mobile-menu-content border-start border-surface">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <span class="fw-bold fs-5 tracking-tight text-body">Menu</span>
            <button class="btn btn-link text-body p-0 mobile-menu-close" aria-label="Close mobile menu">
                <span class="${iconProfile} fs-1">close</span>
            </button>
        </div>
        
        <nav class="d-flex flex-column gap-4 fs-4 fw-semibold ${fontClass}">
            <a href="../home/index.php" class="text-decoration-none text-body transition">Home</a>
            <a href="../fleet/index.php" class="text-decoration-none text-body transition">Fleet</a>
            <a href="../about/index.html" class="text-decoration-none text-body transition">About Us</a>
            <a href="../contact/index.html" class="text-decoration-none text-body transition">Contact</a>
        </nav>
        
        <div class="mt-5 pt-4 border-top border-surface">
             <a href="../contact/index.html#contact"
                class="btn btn-primary-custom w-100 rounded-pill fw-bold py-3 d-flex justify-content-center align-items-center gap-2 shadow"
                style="background-color: var(--primary${colorProfile === 'homepage' ? '' : (colorProfile === 'about' ? '-alt1' : (colorProfile === 'fleet' ? '-alt2' : '-alt3'))});">
                Book Now <span class="${iconProfile} fs-5 ms-1">arrow_forward</span>
            </a>
        </div>
    </div>
</div>`;

        headerPlaceholder.outerHTML = headerHTML;

        // Mobile Menu Toggle Logic
        const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
        const openBtn = document.querySelector('.mobile-menu-btn');
        const closeBtn = document.querySelector('.mobile-menu-close');

        if (mobileMenuOverlay && openBtn && closeBtn) {
            openBtn.addEventListener('click', () => {
                mobileMenuOverlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            });

            closeBtn.addEventListener('click', () => {
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });

            // Close when clicking outside the content block
            mobileMenuOverlay.addEventListener('click', (e) => {
                if (e.target === mobileMenuOverlay) {
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        }

        // Ensure nav active state runs AFTER the HTML is injected
        initNavActiveState();
    }

    // 7. Contact Form Logic
    const contactForm = document.getElementById('contact-form');
    if (contactForm) {
        // Pre-fill fields from URL
        const urlParams = new URLSearchParams(window.location.search);
        const vehicleId = urlParams.get('vehicle_id');
        const vehicleName = urlParams.get('vehicle');
        const subjectSelect = document.getElementById('subject');
        const messageTextarea = document.getElementById('message');
        const vehicleIdInput = document.getElementById('vehicle_id');
        const dateFields = document.querySelectorAll('.booking-dates');
        const pickupInput = document.getElementById('pickupDate');
        const returnInput = document.getElementById('returnDate');

        const toggleDates = () => {
            if (subjectSelect.value === 'Vehicle Booking') {
                dateFields.forEach(el => el.classList.remove('d-none'));
                if (pickupInput) pickupInput.setAttribute('required', 'required');
                if (returnInput) returnInput.setAttribute('required', 'required');
            } else {
                dateFields.forEach(el => el.classList.add('d-none'));
                if (pickupInput) pickupInput.removeAttribute('required');
                if (returnInput) returnInput.removeAttribute('required');
            }
        };

        if (subjectSelect) {
            subjectSelect.addEventListener('change', toggleDates);
        }

        if (vehicleId && vehicleName) {
            if (vehicleIdInput) vehicleIdInput.value = vehicleId;
            if (subjectSelect) subjectSelect.value = 'Vehicle Booking';
            if (messageTextarea) {
                messageTextarea.value = `I am inquiring about booking the ${vehicleName}. Please let me know the availability.`;
            }
            toggleDates();
        }

        contactForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (contactForm.checkValidity()) {
                // Prepare form data
                const formData = new FormData();
                formData.append('firstName', document.getElementById('firstName').value);
                formData.append('lastName', document.getElementById('lastName').value);
                formData.append('email', document.getElementById('email').value);
                formData.append('phone', document.getElementById('phone').value);
                formData.append('subject', document.getElementById('subject').value);
                formData.append('message', document.getElementById('message').value);

                if (vehicleIdInput && vehicleIdInput.value) formData.append('vehicle_id', vehicleIdInput.value);
                if (pickupInput && pickupInput.value) formData.append('pickupDate', pickupInput.value);
                if (returnInput && returnInput.value) formData.append('returnDate', returnInput.value);

                try {
                    const submitBtn = contactForm.querySelector('button[type="submit"]');
                    const originalBtnText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...';

                    const response = await fetch('../api/contact.php', {
                        method: 'POST',
                        body: formData
                    });

                    const result = await response.json();

                    if (result.success) {
                        const successAlert = document.getElementById('form-success');
                        if (successAlert) {
                            successAlert.classList.remove('d-none');
                            successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }
                        contactForm.classList.remove('was-validated');
                        contactForm.reset();
                        toggleDates(); // reset UI

                        setTimeout(() => {
                            if (successAlert) successAlert.classList.add('d-none');
                        }, 5000);
                    } else {
                        alert(result.message || 'There was an error sending your message.');
                    }
                } catch (error) {
                    console.error('Error submitting form:', error);
                    alert('Network error. Please try again later.');
                } finally {
                    const submitBtn = contactForm.querySelector('button[type="submit"]');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<span class="material-icons-round">send</span> Send Message';
                }
            } else {
                contactForm.classList.add('was-validated');
            }
        });
    }

});
