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
 * Uses two stacked image layers so the outgoing image remains visible
 * underneath the incoming image during the cross-fade. This avoids the
 * brief dark/empty flash caused by changing the src of a single <img>.
 */
function initYearbookHeroSlideshow() {
    const wrapper = document.querySelector('.yearbook-wrapper');
    const heroContainer = wrapper?.querySelector('.yb-cover-image');
    const firstImage = heroContainer?.querySelector('img');

    if (!wrapper || !heroContainer || !firstImage) {
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
            if (!Array.isArray(images)) {
                return;
            }

            const urls = images
                .map((image) => image?.url)
                .filter(Boolean);

            if (urls.length <= 1) {
                return;
            }

            const prefersReducedMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            if (prefersReducedMotion) {
                return;
            }

            let currentIndex = Math.max(
                urls.indexOf(firstImage.currentSrc || firstImage.src),
                0
            );

            // Create the second layer once. Both images stay mounted, which
            // makes the transition a true cross-fade instead of a fade-out,
            // src replacement, and fade-in sequence.
            const secondImage = firstImage.cloneNode(true);
            secondImage.removeAttribute('srcset');
            secondImage.removeAttribute('sizes');
            secondImage.classList.add('yb-hero-slideshow-layer');
            secondImage.style.opacity = '0';
            secondImage.style.zIndex = '1';
            secondImage.setAttribute('aria-hidden', 'true');

            firstImage.classList.add('yb-hero-slideshow-layer');
            firstImage.style.zIndex = '2';

            heroContainer.appendChild(secondImage);

            const layers = [firstImage, secondImage];
            let activeLayer = 0;
            let isChanging = false;

            // Preload all selected images so the browser has them ready before
            // they ever become visible.
            urls.forEach((url) => {
                const preload = new Image();
                preload.src = url;
            });

            const showNextImage = () => {
                if (isChanging) {
                    return;
                }

                isChanging = true;

                const nextIndex = (currentIndex + 1) % urls.length;
                const nextUrl = urls[nextIndex];
                const incomingLayerIndex = activeLayer === 0 ? 1 : 0;
                const incomingLayer = layers[incomingLayerIndex];
                const outgoingLayer = layers[activeLayer];

                const nextImage = new Image();

                nextImage.onload = () => {
                    incomingLayer.src = nextUrl;
                    incomingLayer.style.transform = 'scale(1.035)';
                    incomingLayer.style.opacity = '0';

                    // Wait until the new image is painted before starting the
                    // fade. This prevents a visible blank frame.
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            incomingLayer.style.opacity = '1';
                            incomingLayer.style.transform = 'scale(1)';
                            outgoingLayer.style.opacity = '0';
                            outgoingLayer.style.transform = 'scale(1.015)';
                        });
                    });

                    window.setTimeout(() => {
                        currentIndex = nextIndex;
                        activeLayer = incomingLayerIndex;
                        isChanging = false;
                    }, 1250);
                };

                nextImage.onerror = () => {
                    isChanging = false;
                };

                nextImage.src = nextUrl;
            };

            window.setInterval(showNextImage, 3000);
        })
        .catch((error) => {
            console.warn('Yearbook hero slideshow could not be initialized.', error);
        });
}
