import Alpine from 'alpinejs';
import { initSmoothScroll } from "./lib/smooth-scroll";
import { initRevealText } from "./lib/reveal";
import { initCounters } from "./lib/counter";
import { initStagger } from "./lib/stagger";
import { initMagnetic } from "./lib/magnetic";
import { initParallax, initScrollProgress } from "./lib/parallax";

window.Alpine = Alpine;

Alpine.start();

document.addEventListener("DOMContentLoaded", () => {
    initSmoothScroll();
    initRevealText();
    initCounters();
    initStagger();
    initMagnetic();
    initParallax();
    initScrollProgress();

    initYearbookHeroSlideshow();
});

/**
 * Rotate through every hero image selected by the admin.
 *
 * The public home page initially renders the first selected image.
 * We fetch the complete ordered hero-image set and then replace the
 * image every 3 seconds with a soft cross-fade.
 */
function initYearbookHeroSlideshow() {
    const wrapper = document.querySelector('.yearbook-wrapper');
    const heroImage = wrapper?.querySelector('.yb-cover-image img');

    if (!wrapper || !heroImage) {
        return;
    }

    fetch('/yearbook/hero-images', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        credentials: 'same-origin',
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Hero image request failed: ${response.status}`);
            }

            return response.json();
        })
        .then((images) => {
            if (!Array.isArray(images) || images.length <= 1) {
                return;
            }

            const urls = images
                .map((image) => image?.url)
                .filter(Boolean);

            if (urls.length <= 1) {
                return;
            }

            let currentIndex = 0;
            let isChanging = false;

            // Preload every selected image so transitions do not flash.
            urls.forEach((url) => {
                const preload = new Image();
                preload.src = url;
            });

            heroImage.style.transition = 'opacity 700ms ease, transform 1.2s cubic-bezier(.16, 1, .3, 1), filter 700ms ease';

            const showNextImage = () => {
                if (isChanging) {
                    return;
                }

                isChanging = true;
                currentIndex = (currentIndex + 1) % urls.length;
                const nextUrl = urls[currentIndex];

                heroImage.style.opacity = '0';
                heroImage.style.filter = 'saturate(.65) brightness(.85)';

                const nextImage = new Image();

                nextImage.onload = () => {
                    heroImage.src = nextUrl;

                    requestAnimationFrame(() => {
                        heroImage.style.opacity = '1';
                        heroImage.style.filter = 'saturate(.82) brightness(1)';
                    });

                    setTimeout(() => {
                        isChanging = false;
                    }, 750);
                };

                nextImage.onerror = () => {
                    heroImage.style.opacity = '1';
                    heroImage.style.filter = 'saturate(.82) brightness(1)';
                    isChanging = false;
                };

                nextImage.src = nextUrl;
            };

            const prefersReducedMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            // Respect accessibility preferences. The hero remains static.
            if (prefersReducedMotion) {
                return;
            }

            window.setInterval(showNextImage, 3000);
        })
        .catch((error) => {
            console.warn('Yearbook hero slideshow could not be initialized.', error);
        });
}
