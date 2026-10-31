import Swiper from 'swiper';
import { Autoplay, EffectFade, Keyboard, Navigation, A11y } from 'swiper/modules';

const config = window.siteConfig || {};

const pad = (n) => String(n).padStart(2, '0');

/* Selectors match every button set of a slider (header arrows + side arrows on phones) */
function navFor(id) {
    return {
        prevEl: `[data-prev="${id}"]`,
        nextEl: `[data-next="${id}"]`,
    };
}

/* Plays the video of the active slide only */
function syncVideos(swiper) {
    swiper.slides.forEach((slide, index) => {
        const video = slide.querySelector('video');
        if (!video) return;
        if (index === swiper.activeIndex) {
            video.preload = 'auto';
            video.play().catch(() => {});
        } else {
            video.pause();
        }
    });
}

function initHero() {
    const el = document.getElementById('heroSwiper');
    if (!el) return;

    const slides = el.querySelectorAll('.swiper-slide').length;
    const delay = config.sliderDelay || 6000;
    const current = el.querySelector('.hero-counter .current');
    const progress = el.querySelector('.hero-progress span');

    const swiper = new Swiper(el, {
        modules: [Autoplay, EffectFade, Keyboard, Navigation, A11y],
        effect: 'fade',
        fadeEffect: { crossFade: true },
        speed: 1100,
        loop: false,
        rewind: slides > 1,
        allowTouchMove: slides > 1,
        grabCursor: slides > 1,
        keyboard: { enabled: true },
        navigation: navFor('heroSwiper'),
        autoplay: slides > 1 ? { delay, disableOnInteraction: false, pauseOnMouseEnter: false } : false,
        on: {
            init: (s) => syncVideos(s),
            slideChange: (s) => {
                syncVideos(s);
                if (current) current.textContent = pad(s.activeIndex + 1);
            },
            autoplayTimeLeft: (s, time, ratio) => {
                if (progress) progress.style.transform = `scaleX(${1 - ratio})`;
            },
        },
    });

    // Start the slider timer only after the loading screen is gone
    if (document.body.classList.contains('is-loading') && swiper.autoplay) {
        swiper.autoplay.stop();
        document.addEventListener('preloader:done', () => swiper.autoplay.start(), { once: true });
    }
}

/* Every cards slider shares the same behaviour: drag, buttons and autoplay */
/* Cards per view by screen width: one full card on phones */
const PRESETS = {
    services: { 0: 1, 768: 2, 992: 3 },
    projects: { 0: 1, 768: 2, 992: 3, 1200: 4 },
    testimonials: { 0: 1, 768: 2, 1200: 3 },
};

function initCards() {
    document.querySelectorAll('.cards-swiper').forEach((el) => {
        const preset = PRESETS[el.dataset.cards] || PRESETS.services;
        const count = el.querySelectorAll('.swiper-slide').length;
        const breakpoints = {};

        Object.entries(preset).forEach(([width, perView]) => {
            breakpoints[width] = { slidesPerView: perView, spaceBetween: Number(width) >= 992 ? 24 : 16 };
        });

        const maxPerView = Math.max(...Object.values(preset));

        new Swiper(el, {
            modules: [Autoplay, Keyboard, Navigation, A11y],
            speed: 700,
            grabCursor: true,
            watchOverflow: true,
            rewind: count > maxPerView,
            keyboard: { enabled: true, onlyInViewport: true },
            navigation: navFor(el.id),
            autoplay: count > 1 ? { delay: config.cardsDelay || 4000, disableOnInteraction: false, pauseOnMouseEnter: true } : false,
            breakpoints,
        });
    });
}

export function initSliders() {
    initHero();
    initCards();
}
