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
 * underneath the incoming image during a long, gentle cross-fade.
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

            // Keep both images mounted. The incoming image fades in while the
            // outgoing image fades out, so there is never an empty frame.
            const secondImage = firstImage.cloneNode(true);
            secondImage.removeAttribute('srcset');
            secondImage.removeAttribute('sizes');
            secondImage.classList.add('yb-hero-slideshow-layer');
            secondImage.style.opacity = '0';
            secondImage.style.zIndex = '1';
            secondImage.style.willChange = 'opacity';
            secondImage.setAttribute('aria-hidden', 'true');

            firstImage.classList.add('yb-hero-slideshow-layer');
            firstImage.style.zIndex = '2';
            firstImage.style.willChange = 'opacity';

            heroContainer.appendChild(secondImage);

            const layers = [firstImage, secondImage];
            let activeLayer = 0;
            let isChanging = false;

            // A single transition rule keeps the movement calm and consistent.
            // Do not animate transform here because the existing hero parallax
            // also controls transform on the active image.
            layers.forEach((layer) => {
                layer.style.transition = 'opacity 1800ms cubic-bezier(0.22, 0.61, 0.36, 1)';
            });

            // Preload all selected images so every transition is ready before
            // the image becomes visible.
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
                    incomingLayer.style.opacity = '0';

                    // Give the browser a frame to paint the new image before
                    // starting the fade. This creates a true overlap.
                    requestAnimationFrame(() => {
                        requestAnimationFrame(() => {
                            incomingLayer.style.opacity = '1';
                            outgoingLayer.style.opacity = '0';
                        });
                    });

                    window.setTimeout(() => {
                        currentIndex = nextIndex;
                        activeLayer = incomingLayerIndex;
                        isChanging = false;
                    }, 1850);
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
