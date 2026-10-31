import $ from 'jquery';
import Offcanvas from 'bootstrap/js/dist/offcanvas';

/** Solid header after scrolling; on the home page it starts transparent over the slider. */
export function initHeader() {
    const $header = $('#siteHeader');
    if (!$header.length) return;

    let ticking = false;
    const update = () => {
        $header.toggleClass('is-scrolled', window.scrollY > 40);
        ticking = false;
    };

    $(window).on('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(update);
            ticking = true;
        }
    });

    update();

    // Close the mobile menu when a link inside it is followed
    $('#mobileMenu').on('click', 'a[href]:not([href="#"])', function () {
        Offcanvas.getInstance(document.getElementById('mobileMenu'))?.hide();
    });
}
