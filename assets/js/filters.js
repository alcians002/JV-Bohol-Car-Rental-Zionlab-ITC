/**
 * Vehicle Fleet Filter & Sort Module
 * Handles category filtering and year sorting for the fleet grid.
 */

const VEHICLE_CATEGORIES = [
    "all",
    "hatchbacks",
    "sedans",
    "mpvs",
    "pickup-trucks",
    "vans"
];

document.addEventListener('DOMContentLoaded', () => {
    initFleetFilters();
    initSortByYear();
    validateFilters();
});

/**
 * Category Filter Logic
 * Filters vehicle cards by data-category attribute
 */
const initFleetFilters = () => {
    const filterBtns = document.querySelectorAll('.vehicle-filter');
    const vehicleCards = document.querySelectorAll('.vehicle-card');

    if (filterBtns.length === 0 || vehicleCards.length === 0) return;

    filterBtns.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const targetBtn = e.currentTarget;
            const filterValue = targetBtn.getAttribute('data-filter') || 'all';

            // 1. Reset all buttons in the same flex container to inactive
            const siblingBtns = targetBtn.closest('.d-flex').querySelectorAll('.vehicle-filter');
            siblingBtns.forEach(b => {
                b.classList.remove('btn-dark', 'text-white', 'border-0');
                b.classList.add('btn-outline-secondary', 'text-body', 'bg-surface', 'border-surface');
                b.setAttribute('aria-pressed', 'false');
            });

            // 2. Set clicked button to active state
            targetBtn.classList.remove('btn-outline-secondary', 'text-body', 'bg-surface', 'border-surface');
            targetBtn.classList.add('btn-dark', 'text-white', 'border-0');
            targetBtn.setAttribute('aria-pressed', 'true');

            // 3. Filter the cards based on data-category
            vehicleCards.forEach(card => {
                const category = card.getAttribute('data-category') || 'uncategorized';
                let show = (filterValue === 'all' || category.includes(filterValue));

                // 4. Hide/Show the column wrapper preserving layout grid
                const colWrapper = card.closest('div[class*="col-"]');
                if (colWrapper) {
                    if (show) {
                        colWrapper.classList.remove('d-none');
                        colWrapper.classList.add('d-block');
                    } else {
                        colWrapper.classList.remove('d-block');
                        colWrapper.classList.add('d-none');
                    }
                }
            });
        });
    });
};

/**
 * Sort by Year Logic
 * Reorders vehicle card DOM nodes within the grid based on data-year attribute
 */
const initSortByYear = () => {
    const sortSelect = document.getElementById('sort-year');
    const grid = document.getElementById('fleet-grid');

    if (!sortSelect || !grid) return;

    sortSelect.addEventListener('change', () => {
        const sortValue = sortSelect.value;

        if (!sortValue) {
            // Reload page to reset to default database order
            window.location.reload();
            return;
        }

        // Gather all column wrappers in the grid
        const colWrappers = Array.from(grid.children);

        colWrappers.sort((a, b) => {
            const cardA = a.querySelector('.vehicle-card');
            const cardB = b.querySelector('.vehicle-card');

            const yearA = parseInt(cardA ? cardA.getAttribute('data-year') : '0', 10) || 0;
            const yearB = parseInt(cardB ? cardB.getAttribute('data-year') : '0', 10) || 0;

            return sortValue === 'asc' ? (yearA - yearB) : (yearB - yearA);
        });

        // Use DocumentFragment to re-render sorted nodes cleanly
        const fragment = document.createDocumentFragment();
        colWrappers.forEach(col => fragment.appendChild(col));
        grid.appendChild(fragment);
    });
};

/**
 * Optional QA Validation Hook
 * Evaluates nodes on boot to ensure categorizations hit defined limits
 */
const validateFilters = () => {
    const cards = document.querySelectorAll('.vehicle-card');
    if (!cards.length) return;

    let uncategorized = 0;
    const countMap = {};

    VEHICLE_CATEGORIES.forEach(c => countMap[c] = 0);

    cards.forEach(card => {
        const cat = card.getAttribute('data-category');
        if (!cat) uncategorized++;
        else {
            if (countMap[cat] !== undefined) countMap[cat]++;
            else countMap[cat] = 1;
        }
    });

    if (uncategorized > 0) {
        console.warn(`[QA Warning] Found ${uncategorized} vehicle(s) without a data-category mapping!`);
    }
};
