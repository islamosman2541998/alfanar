/**
 * Hides the loading screen once the page has loaded.
 * Kept visible at least MIN ms (no flash) and at most MAX ms (slow video never blocks the page).
 */
const MIN = 700;
const MAX = 4000;

export function initPreloader() {
    const el = document.getElementById('preloader');
    if (!el) return;

    const started = performance.now();
    let done = false;

    const hide = () => {
        if (done) return;
        done = true;
        const wait = Math.max(0, MIN - (performance.now() - started));
        setTimeout(() => {
            el.classList.add('is-done');
            document.body.classList.remove('is-loading');
            document.dispatchEvent(new CustomEvent('preloader:done'));
            setTimeout(() => el.remove(), 700);
        }, wait);
    };

    if (document.readyState === 'complete') hide();
    else window.addEventListener('load', hide, { once: true });

    setTimeout(hide, MAX);

    // Back/forward cache: never show a stuck loader
    window.addEventListener('pageshow', (e) => e.persisted && hide());
}
