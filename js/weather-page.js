/**
 * Day-pill interactions for the weather page.
 *
 * Each pill carries the day's temp + wind as data-* attributes. Clicking a
 * pill updates the big card in place — no page reload — and rewrites the
 * URL with history.replaceState so deep-links still work.
 *
 * If JS is disabled the pill is a plain <a href="?city=..&day=..">, so the
 * server-side fallback still works.
 */
(function () {
    const pills = document.querySelectorAll('.js-day-pill');
    if (pills.length === 0) return;

    const tempEl = document.querySelector('[data-temp]');
    const windEl = document.querySelector('[data-wind]');
    if (!tempEl || !windEl) return;

    pills.forEach((pill) => {
        pill.addEventListener('click', (event) => {
            event.preventDefault();

            const temp = pill.dataset.temp;
            const wind = pill.dataset.wind;
            const day = pill.dataset.day;

            tempEl.innerHTML = temp + '&deg;';
            windEl.textContent = wind + ' km/h Wind';

            pills.forEach((p) => p.parentElement.classList.remove('is-active'));
            pill.parentElement.classList.add('is-active');

            const url = new URL(window.location.href);
            url.searchParams.set('day', day);
            window.history.replaceState(null, '', url.toString());
        });
    });
})();
