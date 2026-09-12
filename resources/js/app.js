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
    initHeroImageOrdering();
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

            layers.forEach((layer) => {
                layer.style.transition = 'opacity 1800ms cubic-bezier(0.22, 0.61, 0.36, 1)';
            });

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

/**
 * Allow administrators to control the exact order of selected hero images.
 * The existing settings page renders the selected images as a "Current order"
 * preview and the selectable images as checkboxes. The preview becomes a
 * drag-and-drop ordering surface, while the existing media_ids[] submission
 * preserves the resulting order without changing the backend contract.
 */
function initHeroImageOrdering() {
    const form = document.querySelector('#hero-images-form');
    const choices = document.querySelector('#hero-images-choices');

    if (!form || !choices) {
        return;
    }

    let preview = document.querySelector('#hero-order-preview');

    // When no hero image has been selected yet, the Blade template does not
    // render a preview. Create it dynamically so the first selected image can
    // immediately be added to the ordering interface.
    if (!preview) {
        const label = document.createElement('p');
        label.className = 'form-field-label';
        label.style.marginBottom = '10px';
        label.textContent = 'Current order';

        preview = document.createElement('div');
        preview.id = 'hero-order-preview';
        preview.style.display = 'flex';
        preview.style.gap = '10px';
        preview.style.flexWrap = 'wrap';
        preview.style.marginBottom = '28px';

        form.insertBefore(label, choices);
        form.insertBefore(preview, choices);
    }

    let draggedCard = null;

    const normalizeUrl = (value) => {
        try {
            return new URL(value, window.location.origin).pathname;
        } catch {
            return value;
        }
    };

    const getCardUrl = (card) => {
        const image = card?.querySelector('img');
        return image ? normalizeUrl(image.currentSrc || image.src) : null;
    };

    const getChoiceUrl = (choice) => {
        const image = choice?.querySelector('img');
        return image ? normalizeUrl(image.currentSrc || image.src) : null;
    };

    const findChoiceForCard = (card) => {
        const url = getCardUrl(card);

        if (!url) {
            return null;
        }

        return Array.from(choices.querySelectorAll('label.choice-item'))
            .find((choice) => {
                const checkbox = choice.querySelector('input[type="checkbox"][name="media_ids[]"]');
                return checkbox?.checked && getChoiceUrl(choice) === url;
            }) || null;
    };

    const refreshNumbers = () => {
        Array.from(preview.children).forEach((card, index) => {
            const number = card.querySelector('[data-hero-order-number]');

            if (number) {
                number.textContent = `#${index + 1}`;
            }
        });
    };

    const syncChoiceOrder = () => {
        Array.from(preview.children).forEach((card) => {
            const choice = findChoiceForCard(card);

            if (choice) {
                choices.appendChild(choice);
            }
        });
    };

    const moveCard = (card, direction) => {
        if (!card) {
            return;
        }

        if (direction === 'up' && card.previousElementSibling) {
            preview.insertBefore(card, card.previousElementSibling);
        }

        if (direction === 'down' && card.nextElementSibling) {
            preview.insertBefore(card.nextElementSibling, card);
        }

        refreshNumbers();
        syncChoiceOrder();
    };

    const decorateCard = (card) => {
        if (card.dataset.heroOrderingReady === 'true') {
            return;
        }

        card.dataset.heroOrderingReady = 'true';
        card.draggable = true;
        card.style.position = 'relative';
        card.style.cursor = 'grab';
        card.style.padding = '8px';
        card.style.border = '1px solid var(--line)';
        card.style.borderRadius = '8px';
        card.style.background = '#ffffff';
        card.style.boxShadow = '0 4px 12px rgba(0, 42, 92, 0.06)';
        card.style.transition = 'transform 180ms ease, box-shadow 180ms ease, border-color 180ms ease';

        const image = card.querySelector('img');
        if (image) {
            image.style.display = 'block';
            image.style.width = '100px';
            image.style.height = '70px';
            image.style.objectFit = 'cover';
            image.style.borderRadius = '6px';
        }

        const number = card.querySelector('span');
        if (number) {
            number.dataset.heroOrderNumber = 'true';
            number.style.fontWeight = '700';
            number.style.letterSpacing = '.4px';
        }

        const controls = document.createElement('div');
        controls.style.display = 'flex';
        controls.style.alignItems = 'center';
        controls.style.justifyContent = 'center';
        controls.style.gap = '4px';
        controls.style.marginTop = '6px';

        const makeButton = (label, title, direction) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.textContent = label;
            button.title = title;
            button.setAttribute('aria-label', title);
            button.style.width = '28px';
            button.style.height = '26px';
            button.style.padding = '0';
            button.style.border = '1px solid var(--line)';
            button.style.borderRadius = '5px';
            button.style.background = '#f7fbff';
            button.style.color = 'var(--ink)';
            button.style.cursor = 'pointer';
            button.style.fontWeight = '700';
            button.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();
                moveCard(card, direction);
            });
            return button;
        };

        controls.appendChild(makeButton('←', 'Move image left', 'up'));
        controls.appendChild(makeButton('→', 'Move image right', 'down'));
        card.appendChild(controls);

        card.addEventListener('dragstart', (event) => {
            draggedCard = card;
            card.style.opacity = '0.55';
            card.style.transform = 'scale(.98)';
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', 'hero-image');
        });

        card.addEventListener('dragend', () => {
            card.style.opacity = '1';
            card.style.transform = '';
            draggedCard = null;
            refreshNumbers();
            syncChoiceOrder();
        });

        card.addEventListener('dragover', (event) => {
            event.preventDefault();

            if (!draggedCard || draggedCard === card) {
                return;
            }

            const rect = card.getBoundingClientRect();
            const insertAfter = event.clientX > rect.left + rect.width / 2;

            if (insertAfter) {
                preview.insertBefore(draggedCard, card.nextSibling);
            } else {
                preview.insertBefore(draggedCard, card);
            }

            refreshNumbers();
        });
    };

    const addPreviewCard = (choice) => {
        const image = choice.querySelector('img');
        const checkbox = choice.querySelector('input[type="checkbox"][name="media_ids[]"]');

        if (!image || !checkbox) {
            return;
        }

        const card = document.createElement('div');
        card.style.width = '116px';
        card.style.textAlign = 'center';

        const previewImage = document.createElement('img');
        previewImage.src = image.currentSrc || image.src;
        previewImage.alt = image.alt || checkbox.value;
        previewImage.loading = 'lazy';
        previewImage.style.width = '100px';
        previewImage.style.height = '70px';
        previewImage.style.objectFit = 'cover';
        previewImage.style.borderRadius = '6px';
        previewImage.style.border = '1px solid var(--line)';

        const number = document.createElement('span');
        number.dataset.heroOrderNumber = 'true';
        number.style.display = 'block';
        number.style.fontSize = '10px';
        number.style.color = 'var(--ink-soft)';
        number.style.marginTop = '4px';

        card.appendChild(previewImage);
        card.appendChild(number);
        preview.appendChild(card);
        decorateCard(card);
        refreshNumbers();
    };

    const removePreviewCard = (choice) => {
        const url = getChoiceUrl(choice);

        if (!url) {
            return;
        }

        const card = Array.from(preview.children)
            .find((candidate) => getCardUrl(candidate) === url);

        card?.remove();
        refreshNumbers();
    };

    Array.from(preview.children).forEach(decorateCard);
    refreshNumbers();

    choices.addEventListener('change', (event) => {
        const checkbox = event.target.closest('input[type="checkbox"][name="media_ids[]"]');

        if (!checkbox) {
            return;
        }

        const choice = checkbox.closest('label.choice-item');

        if (!choice) {
            return;
        }

        if (checkbox.checked) {
            addPreviewCard(choice);
        } else {
            removePreviewCard(choice);
        }

        syncChoiceOrder();
    });

    form.addEventListener('submit', () => {
        syncChoiceOrder();
    });
}
