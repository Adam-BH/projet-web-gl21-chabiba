document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('[data-filter]');
    const cards = document.querySelectorAll('[data-level]');

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const filter = button.dataset.filter;

            buttons.forEach((item) => item.classList.remove('is-active'));
            button.classList.add('is-active');

            cards.forEach((card) => {
                const matches = filter === 'all' || card.dataset.level === filter;
                card.classList.toggle('is-hidden', !matches);
            });
        });
    });

    const accordions = document.querySelectorAll('[data-accordion]');
    accordions.forEach((accordion) => {
        accordion.addEventListener('toggle', () => {
            if (!accordion.open) {
                return;
            }

            accordions.forEach((other) => {
                if (other !== accordion) {
                    other.open = false;
                }
            });
        });
    });
});