/** Counts statistics up from zero the first time they are visible. */
export function initCounters() {
    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const format = new Intl.NumberFormat('en-US');

    const run = (el) => {
        const target = Number(el.dataset.count) || 0;
        const duration = 1800;
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = format.format(Math.round(target * eased));
            if (progress < 1) requestAnimationFrame(step);
        };

        requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
        counters.forEach((el) => (el.textContent = format.format(Number(el.dataset.count))));
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                run(entry.target);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach((el) => observer.observe(el));
}
