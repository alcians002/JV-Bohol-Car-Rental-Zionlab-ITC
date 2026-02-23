/**
 * Admin Dashboard — Client-Side Interactivity (v2)
 * Expanded to handle new vehicle fields in the Edit modal.
 */

document.addEventListener('DOMContentLoaded', () => {

    /* -------------------------------------------------------
     * 0. DARK MODE TOGGLE (Admin)
     * ----------------------------------------------------- */
    const initAdminDarkMode = () => {
        const htmlElement = document.documentElement;
        const savedTheme = localStorage.getItem('theme');
        const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        if (savedTheme === 'dark' || (!savedTheme && systemDark)) {
            htmlElement.setAttribute('data-bs-theme', 'dark');
        } else {
            htmlElement.setAttribute('data-bs-theme', 'light');
        }

        const updateIcon = (theme) => {
            document.querySelectorAll('.admin-dark-toggle').forEach(btn => {
                const darkIcon = btn.querySelector('.dark-icon');
                const lightIcon = btn.querySelector('.light-icon');
                if (darkIcon && lightIcon) {
                    darkIcon.style.display = theme === 'dark' ? 'none' : 'block';
                    lightIcon.style.display = theme === 'dark' ? 'block' : 'none';
                }
            });
        };

        updateIcon(htmlElement.getAttribute('data-bs-theme'));

        document.querySelectorAll('.admin-dark-toggle').forEach(btn => {
            btn.addEventListener('click', () => {
                const current = htmlElement.getAttribute('data-bs-theme');
                const next = current === 'dark' ? 'light' : 'dark';
                htmlElement.setAttribute('data-bs-theme', next);
                localStorage.setItem('theme', next);
                updateIcon(next);
            });
        });
    };
    initAdminDarkMode();

    /* -------------------------------------------------------
     * 1. DELETE VEHICLE (Fetch API)
     * ----------------------------------------------------- */
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name || `Vehicle #${id}`;

            if (!confirm(`Delete "${name}"? This cannot be undone.`)) return;

            try {
                const res = await fetch('index.php?action=delete', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}`
                });
                const data = await res.json();

                if (data.success) {
                    const row = btn.closest('tr');
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => {
                        row.remove();
                        updateStatCards();
                    }, 300);
                    showToast('Vehicle deleted.', 'success');
                } else {
                    showToast(data.message || 'Delete failed.', 'danger');
                }
            } catch (err) {
                console.error(err);
                showToast('Network error. Please try again.', 'danger');
            }
        });
    });

    /* -------------------------------------------------------
     * 2. UPDATE STATUS (Fetch API via dropdown)
     * ----------------------------------------------------- */
    document.querySelectorAll('.status-select').forEach(select => {
        select.addEventListener('change', async () => {
            const id = select.dataset.id;
            const status = select.value;

            const isBookingPage = window.location.search.includes('page=bookings');
            const url = isBookingPage
                ? 'index.php?page=bookings&action=update_status'
                : 'index.php?action=update_status';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&status=${encodeURIComponent(status)}`
                });
                const data = await res.json();

                if (data.success) {
                    if (!isBookingPage) {
                        const row = select.closest('tr');
                        const badge = row.querySelector('.status-badge');
                        badge.textContent = status;
                        badge.className = 'badge rounded-pill status-badge ' + badgeClass(status);
                        updateStatCards();
                        showToast(`Status → ${status}`, 'success');
                    } else {
                        showToast(`Status → ${status}`, 'success');
                        setTimeout(() => window.location.reload(), 800);
                    }
                } else {
                    showToast(data.message || data.error || 'Update failed.', 'danger');
                }
            } catch (err) {
                console.error(err);
                showToast('Network error.', 'danger');
            }
        });
    });

    /* -------------------------------------------------------
     * 2.5 UPDATE PAYMENT STATUS (Fetch API via dropdown)
     * ----------------------------------------------------- */
    document.querySelectorAll('.payment-select').forEach(select => {
        select.addEventListener('change', async () => {
            const id = select.dataset.id;
            const payment = select.value;
            const url = 'index.php?page=bookings&action=update_payment';

            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${encodeURIComponent(id)}&payment_status=${encodeURIComponent(payment)}`
                });
                const data = await res.json();

                if (data.success) {
                    showToast(`Payment → ${payment}`, 'success');
                    setTimeout(() => window.location.reload(), 800);
                } else {
                    showToast(data.message || data.error || 'Update failed.', 'danger');
                }
            } catch (err) {
                console.error(err);
                showToast('Network error.', 'danger');
            }
        });
    });

    /* -------------------------------------------------------
     * 3. EDIT MODAL — Populate all fields via Fetch
     * ----------------------------------------------------- */
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;

            try {
                const res = await fetch(`index.php?action=get&id=${id}`);
                const vehicle = await res.json();

                if (vehicle.error) {
                    showToast('Vehicle not found.', 'danger');
                    return;
                }

                // Core fields
                document.getElementById('editId').value = vehicle.id;
                document.getElementById('editModelName').value = vehicle.model_name;
                document.getElementById('editYear').value = vehicle.year;
                document.getElementById('editStatus').value = vehicle.status;

                // Expanded fields
                document.getElementById('editCategory').value = vehicle.category || 'hatchbacks';
                document.getElementById('editColor').value = vehicle.color || '';
                document.getElementById('editSeating').value = vehicle.seating || '5 Seater';
                document.getElementById('editTransmission').value = vehicle.transmission || 'Auto';
                document.getElementById('editPrice').value = vehicle.price_per_day || 0;
                document.getElementById('editBadge').value = vehicle.badge_label || '';

                // Image
                document.getElementById('editImage').value = '';
                const preview = document.getElementById('editImagePreview');
                if (vehicle.image_path) {
                    preview.src = 'uploads/' + vehicle.image_path;
                    preview.classList.remove('d-none');
                } else {
                    preview.classList.add('d-none');
                }

                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
            } catch (err) {
                console.error(err);
                showToast('Could not load vehicle data.', 'danger');
            }
        });
    });

    /* -------------------------------------------------------
     * 4. LIVE SEARCH
     * ----------------------------------------------------- */
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.toLowerCase();

            // Try to filter fleet table, if it exists
            const fleetRows = document.querySelectorAll('#fleetTableBody tr');
            if (fleetRows.length > 0) {
                fleetRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            }

            // Try to filter bookings table, if it exists
            const bookingRows = document.querySelectorAll('#bookingsTableBody tr');
            if (bookingRows.length > 0) {
                bookingRows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(term) ? '' : 'none';
                });
            }
        });
    }

    /* -------------------------------------------------------
     * HELPERS
     * ----------------------------------------------------- */

    function badgeClass(status) {
        switch (status) {
            case 'Available': return 'badge-available';
            case 'Maintenance': return 'badge-maintenance';
            case 'Out': return 'badge-out';
            default: return 'bg-secondary';
        }
    }

    function updateStatCards() {
        const rows = document.querySelectorAll('#fleetTableBody tr');
        let total = 0, avail = 0, maint = 0, out = 0;
        rows.forEach(row => {
            if (row.style.opacity === '0') return;
            total++;
            const badge = row.querySelector('.status-badge');
            if (!badge) return;
            const s = badge.textContent.trim();
            if (s === 'Available') avail++;
            if (s === 'Maintenance') maint++;
            if (s === 'Out') out++;
        });
        const el = (id, val) => { const e = document.getElementById(id); if (e) e.textContent = val; };
        el('statTotal', total);
        el('statAvailable', avail);
        el('statMaintenance', maint);
        el('statOut', out);
    }

    function showToast(message, type = 'info') {
        document.querySelectorAll('.flash-toast').forEach(t => t.remove());
        const div = document.createElement('div');
        div.className = `flash-toast alert alert-${type} alert-dismissible fade show shadow`;
        div.innerHTML = `${message}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>`;
        document.body.appendChild(div);
        setTimeout(() => {
            div.classList.remove('show');
            setTimeout(() => div.remove(), 300);
        }, 3000);
    }

    /* -------------------------------------------------------
     * 5. SIDEBAR TOGGLE (Mobile)
     * ----------------------------------------------------- */
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');
    let sidebarBackdrop = document.getElementById('sidebarBackdrop');

    if (!sidebarBackdrop) {
        sidebarBackdrop = document.createElement('div');
        sidebarBackdrop.id = 'sidebarBackdrop';
        sidebarBackdrop.className = 'sidebar-backdrop d-lg-none';
        document.body.appendChild(sidebarBackdrop);
    }

    if (sidebarToggle && sidebar && sidebarBackdrop) {
        const toggleSidebar = () => {
            const isShowing = sidebar.classList.contains('show');
            if (isShowing) {
                sidebar.classList.remove('show');
                sidebarBackdrop.classList.remove('show');
                document.body.style.overflow = '';
            } else {
                sidebar.classList.add('show');
                sidebarBackdrop.classList.add('show');
                document.body.style.overflow = 'hidden';
            }
        };

        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarBackdrop.addEventListener('click', toggleSidebar);
    }
});
