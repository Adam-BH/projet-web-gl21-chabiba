document.addEventListener('DOMContentLoaded', () => {
    const filterContainer = document.getElementById('categoryFilters');
    const cards = document.querySelectorAll('.equipment-card');

    if (!filterContainer) return;

    filterContainer.addEventListener('click', (e) => {
        const chip = e.target.closest('.filter-chip');
        if (!chip) return;

        // Update active chip
        filterContainer.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('is-active'));
        chip.classList.add('is-active');

        const cat = chip.dataset.cat;

        cards.forEach(card => {
            if (cat === 'all' || card.dataset.category === cat) {
                card.classList.remove('is-hidden');
                // Re-trigger entrance animation
                card.style.animation = 'none';
                card.offsetHeight; // force reflow
                card.style.animation = '';
            } else {
                card.classList.add('is-hidden');
            }
        });
    });

    // Scroll down functionality
    const scrollIndicator = document.getElementById('scrollIndicator');
    if (scrollIndicator && filterContainer) {
        scrollIndicator.addEventListener('click', () => {
            const yOffset = -20;
            const y = filterContainer.getBoundingClientRect().top + window.scrollY + yOffset;
            window.scrollTo({ top: y, behavior: 'smooth' });
        });
    }
});
