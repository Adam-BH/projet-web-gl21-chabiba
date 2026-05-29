/**
 * Reads each moon disc's data attributes and writes them as CSS custom
 * properties on the ellipse layer, so all visual rules stay in the
 * stylesheet and the markup carries only data.
 *
 * Geometry: the ellipse is full-disc-sized and centred. JS only needs to
 * set its horizontal scale and colour:
 *   - phase_type = crescent → shadow-coloured ellipse, scales the shadow
 *     past the disc midline into the bright half.
 *   - phase_type = gibbous  → bright-coloured ellipse, restores light past
 *     the disc midline into the shadowed half.
 */
(function () {
    document.querySelectorAll('.js-moon-disc').forEach((disc) => {
        const scale = parseFloat(disc.dataset.ellipseScale || '0');
        const fill = disc.dataset.ellipseFill || 'shadow';

        const ellipse = disc.querySelector('.moon-disc__ellipse');
        if (!ellipse) return;

        ellipse.style.background = fill === 'shadow'
            ? 'var(--moon-shadow)'
            : 'var(--moon-bright)';
        ellipse.style.transform = `scaleX(${Math.max(scale, 0)})`;
    });
})();
