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
    initHeroImageManager();
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
 * Hero image manager for Settings > Hero Images.
 *
 * The existing Blade markup supplies the complete media pool and selected
 * checkboxes. This function upgrades that markup into a visual media picker:
 * - all eligible photos remain visible in the library
 * - clicking a photo selects/deselects it
 * - selected photos appear in an ordered strip at the top
 * - selected photos can be dragged to change order
 * - hidden inputs submit the actual media IDs in the exact visual order
 * - search filters the media library
 * - a lightweight slideshow preview lets the admin review the result
 */
function initHeroImageManager() {
    const form = document.querySelector('#hero-images-form');
    const choices = document.querySelector('#hero-images-choices');

    if (!form || !choices || form.dataset.heroManagerReady === 'true') {
        return;
    }

    form.dataset.heroManagerReady = 'true';

    const style = document.createElement('style');
    style.textContent = `
        .hero-manager {
            display: grid;
            gap: 28px;
        }
        .hero-manager-section {
            border: 1px solid var(--line, #d8e3ef);
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
        }
        .hero-manager-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 20px 22px;
            border-bottom: 1px solid var(--line, #d8e3ef);
            background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%);
        }
        .hero-manager-heading-main h3 {
            margin: 0;
            color: var(--ink, #002a5c);
            font: 700 17px "Merriweather", serif;
        }
        .hero-manager-heading-main p {
            margin: 5px 0 0;
            color: var(--ink-soft, #64748b);
            font-size: 11px;
            line-height: 1.5;
        }
        .hero-manager-count {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 32px;
            padding: 0 11px;
            border: 1px solid #d8e3ef;
            border-radius: 999px;
            background: #f7fbff;
            color: var(--ink, #002a5c);
            font: 700 11px "Inter", sans-serif;
            white-space: nowrap;
        }
        .hero-selected-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
            padding: 20px;
            min-height: 150px;
        }
        .hero-selected-empty {
            grid-column: 1 / -1;
            display: grid;
            place-items: center;
            min-height: 130px;
            padding: 20px;
            border: 1px dashed #b9c9d8;
            border-radius: 10px;
            background: #f9fbfd;
            color: var(--ink-soft, #64748b);
            text-align: center;
        }
        .hero-selected-empty strong {
            display: block;
            margin-bottom: 5px;
            color: var(--ink, #002a5c);
            font: 700 14px "Merriweather", serif;
        }
        .hero-selected-card {
            position: relative;
            padding: 8px;
            border: 1px solid #d8e3ef;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(0, 42, 92, .06);
            cursor: grab;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .hero-selected-card:hover {
            transform: translateY(-2px);
            border-color: #8ca9c3;
            box-shadow: 0 9px 24px rgba(0, 42, 92, .1);
        }
        .hero-selected-card.is-dragging {
            opacity: .45;
            transform: scale(.98);
        }
        .hero-selected-card.is-drag-target {
            border-color: #ffb034;
            box-shadow: 0 0 0 3px rgba(255, 176, 52, .14);
        }
        .hero-selected-image-wrap {
            position: relative;
            aspect-ratio: 16 / 10;
            overflow: hidden;
            border-radius: 8px;
            background: #e7f0fa;
        }
        .hero-selected-image-wrap img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-selected-number {
            position: absolute;
            top: 7px;
            left: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 30px;
            height: 25px;
            padding: 0 7px;
            border-radius: 7px;
            background: rgba(0, 42, 92, .92);
            color: #fff;
            font: 700 10px "Inter", sans-serif;
            letter-spacing: .4px;
        }
        .hero-selected-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            padding: 8px 2px 2px;
        }
        .hero-selected-name {
            min-width: 0;
            color: var(--ink, #002a5c);
            font-size: 10px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .hero-selected-remove {
            flex: 0 0 auto;
            border: 0;
            padding: 3px 5px;
            background: transparent;
            color: #64748b;
            font: 700 10px "Inter", sans-serif;
            cursor: pointer;
        }
        .hero-selected-remove:hover { color: #c0522a; }
        .hero-library-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--line, #d8e3ef);
            background: #f7fbff;
        }
        .hero-library-search {
            flex: 1;
            min-width: 180px;
            height: 40px;
            padding: 0 13px;
            border: 1px solid #d8e3ef;
            border-radius: 8px;
            background: #fff;
            color: var(--ink, #002a5c);
            font: 12px "Inter", sans-serif;
            outline: none;
        }
        .hero-library-search:focus {
            border-color: #8ca9c3;
            box-shadow: 0 0 0 3px rgba(0, 42, 92, .06);
        }
        .hero-library-meta {
            color: var(--ink-soft, #64748b);
            font-size: 10px;
            white-space: nowrap;
        }
        .hero-library-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            gap: 14px;
            padding: 20px;
        }
        .hero-library-card {
            position: relative;
            min-width: 0;
            border: 1px solid #d8e3ef;
            border-radius: 12px;
            background: #fff;
            overflow: hidden;
            cursor: pointer;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        }
        .hero-library-card:hover {
            transform: translateY(-2px);
            border-color: #8ca9c3;
            box-shadow: 0 9px 24px rgba(0, 42, 92, .09);
        }
        .hero-library-card.is-selected {
            border: 2px solid #ffb034;
            box-shadow: 0 0 0 3px rgba(255, 176, 52, .12);
        }
        .hero-library-image {
            position: relative;
            aspect-ratio: 16 / 10;
            background: #e7f0fa;
        }
        .hero-library-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .hero-library-check {
            position: absolute;
            top: 9px;
            right: 9px;
            display: grid;
            place-items: center;
            width: 29px;
            height: 29px;
            border-radius: 50%;
            background: rgba(255,255,255,.95);
            border: 1px solid rgba(0,42,92,.12);
            color: var(--ink, #002a5c);
            font-size: 15px;
            font-weight: 800;
            box-shadow: 0 3px 10px rgba(0,0,0,.1);
        }
        .hero-library-card.is-selected .hero-library-check {
            background: #002a5c;
            color: #fff;
            border-color: #002a5c;
        }
        .hero-library-details {
            padding: 10px 11px 11px;
        }
        .hero-library-name {
            color: var(--ink, #002a5c);
            font-size: 11px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .hero-library-status {
            margin-top: 3px;
            color: var(--ink-soft, #64748b);
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }
        .hero-library-empty {
            grid-column: 1 / -1;
            padding: 36px 20px;
            border: 1px dashed #d8e3ef;
            color: var(--ink-soft, #64748b);
            text-align: center;
            font-size: 12px;
        }
        .hero-manager-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 20px;
            border-top: 1px solid var(--line, #d8e3ef);
            background: #f7fbff;
        }
        .hero-preview-button {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            min-height: 38px;
            padding: 0 14px;
            border: 1px solid #b9c9d8;
            border-radius: 8px;
            background: #fff;
            color: var(--ink, #002a5c);
            font: 700 11px "Inter", sans-serif;
            cursor: pointer;
        }
        .hero-preview-button:hover { border-color: #002a5c; }
        .hero-manager-note {
            color: var(--ink-soft, #64748b);
            font-size: 10px;
            line-height: 1.5;
        }
        .hero-preview-modal {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: grid;
            place-items: center;
            padding: 24px;
            background: rgba(0, 18, 42, .82);
            backdrop-filter: blur(5px);
        }
        .hero-preview-dialog {
            position: relative;
            width: min(920px, 100%);
            background: #071a33;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,.35);
        }
        .hero-preview-stage {
            position: relative;
            aspect-ratio: 16 / 9;
            background: #001b3d;
        }
        .hero-preview-stage img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 700ms ease;
        }
        .hero-preview-stage img.is-active { opacity: 1; }
        .hero-preview-close {
            position: absolute;
            top: 14px;
            right: 14px;
            z-index: 2;
            width: 36px;
            height: 36px;
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 50%;
            background: rgba(0,0,0,.35);
            color: #fff;
            font-size: 20px;
            cursor: pointer;
        }
        .hero-preview-caption {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 13px 16px;
            color: #fff;
            font-size: 11px;
        }
        @media (max-width: 700px) {
            .hero-library-toolbar,
            .hero-manager-actions { align-items: stretch; flex-direction: column; }
            .hero-library-search { width: 100%; }
            .hero-library-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); padding: 12px; gap: 9px; }
            .hero-selected-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); padding: 12px; gap: 9px; }
            .hero-manager-heading { align-items: flex-start; }
        }
    `;
    document.head.appendChild(style);

    const originalChoices = Array.from(
        choices.querySelectorAll('label.choice-item')
    );

    if (!originalChoices.length) {
        return;
    }

    const selectedIds = new Set(
        originalChoices
            .map((choice) => choice.querySelector('input[type="checkbox"][name="media_ids[]"]'))
            .filter((input) => input?.checked)
            .map((input) => String(input.value))
    );

    const selectedOrder = originalChoices
        .map((choice) => choice.querySelector('input[type="checkbox"][name="media_ids[]"]'))
        .filter((input) => input?.checked)
        .map((input) => String(input.value));

    const originalChoiceMap = new Map(
        originalChoices.map((choice) => {
            const input = choice.querySelector('input[type="checkbox"][name="media_ids[]"]');
            return [String(input?.value || ''), choice];
        })
    );

    const getImage = (choice) => choice?.querySelector('img');
    const getInput = (choice) => choice?.querySelector('input[type="checkbox"]');
    const getName = (choice) => {
        const input = getInput(choice);
        const labelText = choice.querySelector('span')?.textContent?.trim();
        return labelText || input?.value || 'Image';
    };

    const manager = document.createElement('div');
    manager.className = 'hero-manager';

    const selectedSection = document.createElement('section');
    selectedSection.className = 'hero-manager-section';
    selectedSection.innerHTML = `
        <div class="hero-manager-heading">
            <div class="hero-manager-heading-main">
                <h3>Selected Hero Images</h3>
                <p>These are the images visitors will see. Drag them to change the slideshow order.</p>
            </div>
            <span class="hero-manager-count"><strong data-hero-selected-count>0</strong> selected</span>
        </div>
        <div class="hero-selected-grid" data-hero-selected-grid></div>
        <div class="hero-manager-actions">
            <span class="hero-manager-note">The first image becomes the opening hero image.</span>
            <button type="button" class="hero-preview-button" data-hero-preview>▶ Preview slideshow</button>
        </div>
    `;

    const librarySection = document.createElement('section');
    librarySection.className = 'hero-manager-section';
    librarySection.innerHTML = `
        <div class="hero-manager-heading">
            <div class="hero-manager-heading-main">
                <h3>Media Library</h3>
                <p>Select any photograph below to add or remove it from the homepage hero.</p>
            </div>
        </div>
        <div class="hero-library-toolbar">
            <input type="search" class="hero-library-search" placeholder="Search photos..." aria-label="Search hero photos">
            <span class="hero-library-meta" data-hero-library-meta></span>
        </div>
        <div class="hero-library-grid" data-hero-library-grid></div>
    `;

    const selectedGrid = selectedSection.querySelector('[data-hero-selected-grid]');
    const libraryGrid = librarySection.querySelector('[data-hero-library-grid]');
    const selectedCount = selectedSection.querySelector('[data-hero-selected-count]');
    const libraryMeta = librarySection.querySelector('[data-hero-library-meta]');
    const searchInput = librarySection.querySelector('.hero-library-search');
    const previewButton = selectedSection.querySelector('[data-hero-preview]');

    // Move the existing choice labels into an invisible compatibility container.
    // Their checkbox values remain the source of truth for the media IDs.
    choices.innerHTML = '';
    choices.style.display = 'none';

    const hiddenOrderContainer = document.createElement('div');
    hiddenOrderContainer.dataset.heroHiddenOrder = 'true';
    form.appendChild(hiddenOrderContainer);

    manager.appendChild(selectedSection);
    manager.appendChild(librarySection);
    form.insertBefore(manager, choices);

    const selectedOrderState = [...selectedOrder];
    let draggedId = null;

    const syncSubmissionInputs = () => {
        hiddenOrderContainer.innerHTML = '';

        selectedOrderState.forEach((id) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'media_ids[]';
            input.value = id;
            hiddenOrderContainer.appendChild(input);
        });

        originalChoices.forEach((choice) => {
            const input = getInput(choice);
            if (input) {
                input.name = '';
                input.checked = selectedIds.has(String(input.value));
            }
        });
    };

    const updateCount = () => {
        selectedCount.textContent = String(selectedOrderState.length);
    };

    const makeSelectedCard = (id, position) => {
        const choice = originalChoiceMap.get(String(id));
        const image = getImage(choice);

        if (!choice || !image) {
            return null;
        }

        const card = document.createElement('article');
        card.className = 'hero-selected-card';
        card.draggable = true;
        card.dataset.heroId = String(id);
        card.innerHTML = `
            <div class="hero-selected-image-wrap">
                <img src="${image.currentSrc || image.src}" alt="${(image.alt || getName(choice)).replace(/"/g, '&quot;')}">
                <span class="hero-selected-number">${String(position + 1).padStart(2, '0')}</span>
            </div>
            <div class="hero-selected-info">
                <span class="hero-selected-name" title="${getName(choice).replace(/"/g, '&quot;')}">${getName(choice)}</span>
                <button type="button" class="hero-selected-remove" aria-label="Remove ${getName(choice)}">Remove</button>
            </div>
        `;

        card.querySelector('.hero-selected-remove').addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleSelection(String(id), false);
        });

        card.addEventListener('dragstart', (event) => {
            draggedId = String(id);
            card.classList.add('is-dragging');
            event.dataTransfer.effectAllowed = 'move';
            event.dataTransfer.setData('text/plain', String(id));
        });

        card.addEventListener('dragend', () => {
            draggedId = null;
            card.classList.remove('is-dragging');
            selectedGrid.querySelectorAll('.is-drag-target').forEach((node) => node.classList.remove('is-drag-target'));
        });

        card.addEventListener('dragover', (event) => {
            event.preventDefault();

            if (!draggedId || draggedId === String(id)) {
                return;
            }

            card.classList.add('is-drag-target');

            const fromIndex = selectedOrderState.indexOf(draggedId);
            const targetIndex = selectedOrderState.indexOf(String(id));

            if (fromIndex < 0 || targetIndex < 0 || fromIndex === targetIndex) {
                return;
            }

            selectedOrderState.splice(fromIndex, 1);
            selectedOrderState.splice(targetIndex, 0, draggedId);
            renderSelected();
            syncSubmissionInputs();
        });

        card.addEventListener('dragleave', () => card.classList.remove('is-drag-target'));

        return card;
    };

    const renderSelected = () => {
        selectedGrid.innerHTML = '';

        if (!selectedOrderState.length) {
            selectedGrid.innerHTML = `
                <div class="hero-selected-empty">
                    <div>
                        <strong>No hero images selected yet</strong>
                        Choose photographs from the Media Library below.
                    </div>
                </div>
            `;
        } else {
            selectedOrderState.forEach((id, index) => {
                const card = makeSelectedCard(id, index);
                if (card) {
                    selectedGrid.appendChild(card);
                }
            });
        }

        updateCount();
        syncSubmissionInputs();
        renderLibraryState();
    };

    const renderLibraryState = () => {
        const query = searchInput.value.trim().toLowerCase();
        const cards = Array.from(libraryGrid.querySelectorAll('.hero-library-card'));
        let visible = 0;

        cards.forEach((card) => {
            const name = card.dataset.heroName || '';
            const matches = !query || name.includes(query);
            card.hidden = !matches;
            if (matches) visible += 1;

            const id = card.dataset.heroId;
            card.classList.toggle('is-selected', selectedIds.has(String(id)));
            const check = card.querySelector('.hero-library-check');
            const status = card.querySelector('.hero-library-status');
            if (check) check.textContent = selectedIds.has(String(id)) ? '✓' : '+';
            if (status) status.textContent = selectedIds.has(String(id)) ? 'Selected for hero' : 'Available';
        });

        libraryMeta.textContent = `${visible} photo${visible === 1 ? '' : 's'} shown`;
    };

    const toggleSelection = (id, selected) => {
        id = String(id);

        if (selected) {
            if (!selectedIds.has(id)) {
                selectedIds.add(id);
                selectedOrderState.push(id);
            }
        } else {
            selectedIds.delete(id);
            const index = selectedOrderState.indexOf(id);
            if (index !== -1) selectedOrderState.splice(index, 1);
        }

        renderSelected();
    };

    originalChoices.forEach((choice) => {
        const input = getInput(choice);
        const image = getImage(choice);

        if (!input || !image) {
            return;
        }

        const id = String(input.value);
        const card = document.createElement('article');
        card.className = 'hero-library-card';
        card.dataset.heroId = id;
        card.dataset.heroName = getName(choice).toLowerCase();
        card.tabIndex = 0;
        card.setAttribute('role', 'button');
        card.setAttribute('aria-pressed', selectedIds.has(id) ? 'true' : 'false');

        const imageSrc = image.currentSrc || image.src;
        card.innerHTML = `
            <div class="hero-library-image">
                <img src="${imageSrc}" alt="${(image.alt || getName(choice)).replace(/"/g, '&quot;')}" loading="lazy">
                <span class="hero-library-check">${selectedIds.has(id) ? '✓' : '+'}</span>
            </div>
            <div class="hero-library-details">
                <div class="hero-library-name" title="${getName(choice).replace(/"/g, '&quot;')}">${getName(choice)}</div>
                <div class="hero-library-status">${selectedIds.has(id) ? 'Selected for hero' : 'Available'}</div>
            </div>
        `;

        const activate = () => {
            const next = !selectedIds.has(id);
            input.checked = next;
            toggleSelection(id, next);
            card.setAttribute('aria-pressed', next ? 'true' : 'false');
        };

        card.addEventListener('click', activate);
        card.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                activate();
            }
        });

        libraryGrid.appendChild(card);
    });

    searchInput.addEventListener('input', renderLibraryState);

    form.addEventListener('submit', () => {
        syncSubmissionInputs();
    });

    const openPreview = () => {
        const urls = selectedOrderState
            .map((id) => {
                const image = getImage(originalChoiceMap.get(id));
                return image ? (image.currentSrc || image.src) : null;
            })
            .filter(Boolean);

        if (!urls.length) {
            return;
        }

        const modal = document.createElement('div');
        modal.className = 'hero-preview-modal';
        modal.innerHTML = `
            <div class="hero-preview-dialog" role="dialog" aria-modal="true" aria-label="Hero slideshow preview">
                <button type="button" class="hero-preview-close" aria-label="Close preview">×</button>
                <div class="hero-preview-stage"></div>
                <div class="hero-preview-caption">
                    <span>Homepage hero preview</span>
                    <span data-preview-counter>01 / ${String(urls.length).padStart(2, '0')}</span>
                </div>
            </div>
        `;

        const stage = modal.querySelector('.hero-preview-stage');
        const counter = modal.querySelector('[data-preview-counter]');
        const close = () => {
            if (timer) window.clearInterval(timer);
            modal.remove();
            document.removeEventListener('keydown', escapeHandler);
        };
        const escapeHandler = (event) => {
            if (event.key === 'Escape') close();
        };

        urls.forEach((url, index) => {
            const image = document.createElement('img');
            image.src = url;
            image.alt = `Hero preview ${index + 1}`;
            if (index === 0) image.classList.add('is-active');
            stage.appendChild(image);
        });

        const layers = Array.from(stage.querySelectorAll('img'));
        let current = 0;
        const timer = urls.length > 1 ? window.setInterval(() => {
            layers[current].classList.remove('is-active');
            current = (current + 1) % layers.length;
            layers[current].classList.add('is-active');
            counter.textContent = `${String(current + 1).padStart(2, '0')} / ${String(urls.length).padStart(2, '0')}`;
        }, 3000) : null;

        modal.querySelector('.hero-preview-close').addEventListener('click', close);
        modal.addEventListener('click', (event) => {
            if (event.target === modal) close();
        });
        document.addEventListener('keydown', escapeHandler);
        document.body.appendChild(modal);
    };

    previewButton.addEventListener('click', openPreview);

    // The existing save button remains the actual form submit action.
    const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submitButton) {
        submitButton.style.minHeight = '42px';
        submitButton.style.padding = '0 18px';
        submitButton.style.borderRadius = '8px';
    }

    renderSelected();
}
