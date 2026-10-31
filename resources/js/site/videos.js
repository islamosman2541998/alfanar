/**
 * Card videos (data-inview) play only while they are on screen,
 * so a page full of cards never downloads or decodes hidden videos.
 */
export function initInviewVideos() {
    const videos = document.querySelectorAll('video[data-inview]');
    if (!videos.length || !('IntersectionObserver' in window)) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(({ target, isIntersecting }) => {
            if (isIntersecting) {
                target.preload = 'auto';
                target.play().catch(() => {});
            } else {
                target.pause();
            }
        });
    }, { threshold: 0.5 });

    videos.forEach((video) => observer.observe(video));
}
