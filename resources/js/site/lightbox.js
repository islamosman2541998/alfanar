import GLightbox from 'glightbox';

/** Full quality images and videos for galleries. */
export function initLightbox() {
    if (!document.querySelector('.glightbox')) return;

    GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true,
        openEffect: 'fade',
        closeEffect: 'fade',
        plyr: { config: { muted: false, hideControls: true } },
    });
}
