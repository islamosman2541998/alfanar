/** Fades elements marked with data-reveal in when they enter the viewport. */
export function initReveal() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!items.length) return;

    if (!('IntersectionObserver' in window)) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    // Small stagger for siblings revealed together (grids)
    items.forEach((el) => {
        const grid = el.parentElement?.parentElement;
        if (!grid) return;
        const siblings = [...grid.querySelectorAll(':scope > * > [data-reveal]')];
        const index = siblings.indexOf(el);
        if (index > 0) el.style.setProperty('--delay', `${Math.min(index, 5) * 0.08}s`);
    });

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });

    items.forEach((el) => observer.observe(el));
}
