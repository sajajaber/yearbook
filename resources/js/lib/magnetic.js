export function initMagnetic(selector = ".btn-cta, .nav-box") {
    if (!window.matchMedia("(pointer: coarse)").matches) {
        document.querySelectorAll(selector).forEach((el) => {
            const strength = 0.35;

            el.addEventListener("mousemove", (e) => {
                const rect = el.getBoundingClientRect();
                const x = e.clientX - rect.left - rect.width / 2;
                const y = e.clientY - rect.top - rect.height / 2;
                el.style.transform = `translate(${x * strength}px, ${y * strength}px)`;
            });

            el.addEventListener("mouseleave", () => {
                el.style.transform = "translate(0, 0)";
            });
        });
    }

    initYearbookGalleryLightbox();
}

function initYearbookGalleryLightbox() {
    const items = Array.from(document.querySelectorAll(
        ".event-gallery-item, .graduation-gallery-item"
    )).filter((item) => item.querySelector("img"));

    if (!items.length || document.querySelector("#yearbookGalleryLightbox")) {
        return;
    }

    const images = items.map((item) => {
        const image = item.querySelector("img");
        const caption = item.querySelector(
            ".event-gallery-caption, .gallery-caption, .gallery-overlay"
        );

        // Remove the old event inline handler so the gallery opens in-place.
        item.onclick = null;

        return {
            src: image.currentSrc || image.src,
            alt: image.alt || "Yearbook gallery image",
            caption: caption?.textContent.trim() || image.alt || "",
            item,
        };
    });

    const overlay = document.createElement("div");
    overlay.id = "yearbookGalleryLightbox";
    overlay.className = "yearbook-gallery-lightbox";
    overlay.setAttribute("aria-hidden", "true");
    overlay.innerHTML = `
        <div class="yearbook-gallery-backdrop" data-gallery-close></div>
        <button type="button" class="yearbook-gallery-close" aria-label="Close gallery" data-gallery-close>×</button>
        <button type="button" class="yearbook-gallery-nav yearbook-gallery-prev" aria-label="Previous image">‹</button>
        <div class="yearbook-gallery-stage" role="dialog" aria-modal="true" aria-label="Image gallery">
            <div class="yearbook-gallery-counter" aria-live="polite"></div>
            <img class="yearbook-gallery-image" alt="">
            <div class="yearbook-gallery-caption"></div>
        </div>
        <button type="button" class="yearbook-gallery-nav yearbook-gallery-next" aria-label="Next image">›</button>
    `;

    document.body.appendChild(overlay);

    const style = document.createElement("style");
    style.textContent = `
        .yearbook-gallery-lightbox {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: grid;
            place-items: center;
            padding: 34px 70px;
            visibility: hidden;
            opacity: 0;
            pointer-events: none;
            transition: opacity 220ms ease, visibility 220ms ease;
        }

        .yearbook-gallery-lightbox.is-open {
            visibility: visible;
            opacity: 1;
            pointer-events: auto;
        }

        .yearbook-gallery-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 12, 28, .94);
            backdrop-filter: blur(12px);
        }

        .yearbook-gallery-stage {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: min(100%, 1500px);
            height: min(100%, 900px);
            min-width: 0;
        }

        .yearbook-gallery-image {
            display: block;
            width: auto;
            height: auto;
            max-width: min(92vw, 1500px);
            max-height: 82vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 28px 90px rgba(0, 0, 0, .42);
            user-select: none;
            -webkit-user-drag: none;
            animation: yearbookGalleryImageIn 260ms ease both;
        }

        .yearbook-gallery-counter {
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 3;
            padding: 8px 13px;
            border: 1px solid rgba(255,255,255,.16);
            border-radius: 999px;
            background: rgba(0, 27, 61, .72);
            color: #fff;
            font: 700 11px/1 "Inter", sans-serif;
            letter-spacing: .08em;
            backdrop-filter: blur(10px);
        }

        .yearbook-gallery-caption {
            position: absolute;
            right: 0;
            bottom: 0;
            left: 0;
            max-width: min(850px, 88vw);
            margin: 0 auto;
            padding: 12px 18px;
            border-radius: 12px;
            background: rgba(0, 27, 61, .72);
            color: rgba(255,255,255,.94);
            font: 500 13px/1.55 "Inter", sans-serif;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .yearbook-gallery-caption:empty {
            display: none;
        }

        .yearbook-gallery-close,
        .yearbook-gallery-nav {
            position: absolute;
            z-index: 5;
            display: grid;
            place-items: center;
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(0, 27, 61, .74);
            color: #fff;
            cursor: pointer;
            backdrop-filter: blur(10px);
            transition: transform 180ms ease, background 180ms ease, border-color 180ms ease;
        }

        .yearbook-gallery-close:hover,
        .yearbook-gallery-nav:hover {
            background: rgba(255, 193, 7, .96);
            border-color: rgba(255, 193, 7, .96);
            color: #002a5c;
            transform: scale(1.06);
        }

        .yearbook-gallery-close {
            top: 22px;
            right: 24px;
            width: 46px;
            height: 46px;
            border-radius: 50%;
            font-size: 30px;
            font-weight: 300;
            line-height: 1;
        }

        .yearbook-gallery-nav {
            top: 50%;
            width: 54px;
            height: 54px;
            margin-top: -27px;
            border-radius: 50%;
            font-size: 43px;
            font-weight: 300;
            line-height: 1;
        }

        .yearbook-gallery-prev { left: 18px; }
        .yearbook-gallery-next { right: 18px; }

        .yearbook-gallery-nav:disabled {
            opacity: .35;
            cursor: default;
            transform: none;
        }

        @keyframes yearbookGalleryImageIn {
            from { opacity: 0; transform: scale(.985); }
            to { opacity: 1; transform: scale(1); }
        }

        @media (max-width: 700px) {
            .yearbook-gallery-lightbox {
                padding: 28px 14px 24px;
            }

            .yearbook-gallery-image {
                max-width: 94vw;
                max-height: 76vh;
                border-radius: 5px;
            }

            .yearbook-gallery-close {
                top: 12px;
                right: 12px;
                width: 42px;
                height: 42px;
            }

            .yearbook-gallery-nav {
                top: auto;
                bottom: 20px;
                width: 46px;
                height: 46px;
                margin-top: 0;
                font-size: 35px;
            }

            .yearbook-gallery-prev { left: 22px; }
            .yearbook-gallery-next { right: 22px; }

            .yearbook-gallery-counter {
                top: 2px;
                font-size: 10px;
            }

            .yearbook-gallery-caption {
                bottom: 74px;
                padding: 9px 12px;
                font-size: 11px;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .yearbook-gallery-lightbox,
            .yearbook-gallery-image,
            .yearbook-gallery-close,
            .yearbook-gallery-nav {
                transition: none !important;
                animation: none !important;
            }
        }

        body.yearbook-gallery-open {
            overflow: hidden;
        }
    `;
    document.head.appendChild(style);

    const backdrop = overlay.querySelector(".yearbook-gallery-backdrop");
    const image = overlay.querySelector(".yearbook-gallery-image");
    const counter = overlay.querySelector(".yearbook-gallery-counter");
    const caption = overlay.querySelector(".yearbook-gallery-caption");
    const previous = overlay.querySelector(".yearbook-gallery-prev");
    const next = overlay.querySelector(".yearbook-gallery-next");
    const close = overlay.querySelector(".yearbook-gallery-close");

    let currentIndex = 0;
    let touchStartX = 0;
    let touchStartY = 0;
    let lastFocusedElement = null;

    const render = () => {
        const current = images[currentIndex];
        if (!current) return;

        image.src = current.src;
        image.alt = current.alt;
        counter.textContent = `${currentIndex + 1} / ${images.length}`;
        caption.textContent = current.caption;
        previous.disabled = images.length <= 1;
        next.disabled = images.length <= 1;

        image.style.animation = "none";
        void image.offsetWidth;
        image.style.animation = "yearbookGalleryImageIn 260ms ease both";
    };

    const open = (index) => {
        currentIndex = Math.max(0, Math.min(index, images.length - 1));
        lastFocusedElement = document.activeElement;
        render();
        overlay.classList.add("is-open");
        overlay.setAttribute("aria-hidden", "false");
        document.body.classList.add("yearbook-gallery-open");
        close.focus({ preventScroll: true });
    };

    const closeLightbox = () => {
        overlay.classList.remove("is-open");
        overlay.setAttribute("aria-hidden", "true");
        document.body.classList.remove("yearbook-gallery-open");
        image.removeAttribute("src");

        if (lastFocusedElement && typeof lastFocusedElement.focus === "function") {
            lastFocusedElement.focus({ preventScroll: true });
        }
    };

    const goTo = (direction) => {
        if (images.length <= 1) return;
        currentIndex = (currentIndex + direction + images.length) % images.length;
        render();
    };

    items.forEach((item, index) => {
        item.addEventListener("click", (event) => {
            if (!item.querySelector("img")) return;
            event.preventDefault();
            event.stopPropagation();
            open(index);
        });

        item.setAttribute("tabindex", "0");
        item.setAttribute("role", "button");
        item.setAttribute("aria-label", `Open gallery image ${index + 1} of ${images.length}`);

        item.addEventListener("keydown", (event) => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                open(index);
            }
        });
    });

    previous.addEventListener("click", () => goTo(-1));
    next.addEventListener("click", () => goTo(1));
    close.addEventListener("click", closeLightbox);
    backdrop.addEventListener("click", closeLightbox);

    document.addEventListener("keydown", (event) => {
        if (!overlay.classList.contains("is-open")) return;

        if (event.key === "Escape") {
            event.preventDefault();
            closeLightbox();
        } else if (event.key === "ArrowLeft") {
            event.preventDefault();
            goTo(-1);
        } else if (event.key === "ArrowRight") {
            event.preventDefault();
            goTo(1);
        }
    });

    overlay.addEventListener("touchstart", (event) => {
        const touch = event.changedTouches[0];
        touchStartX = touch.clientX;
        touchStartY = touch.clientY;
    }, { passive: true });

    overlay.addEventListener("touchend", (event) => {
        const touch = event.changedTouches[0];
        const deltaX = touch.clientX - touchStartX;
        const deltaY = touch.clientY - touchStartY;

        if (Math.abs(deltaX) < 45 || Math.abs(deltaX) < Math.abs(deltaY)) {
            return;
        }

        goTo(deltaX < 0 ? 1 : -1);
    }, { passive: true });
}
