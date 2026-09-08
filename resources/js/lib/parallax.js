export function initParallax() {
    const shapes = document.querySelectorAll(".hero-float-shape");
    if (!shapes.length) return;

    window.addEventListener(
        "scroll",
        () => {
            const y = window.scrollY;
            shapes.forEach((shape, i) => {
                const speed = 0.15 + i * 0.08;
                shape.style.transform += ` translateY(${y * speed}px)`;
            });
        },
        { passive: true },
    );
}

export function initScrollProgress() {
    const bar = document.createElement("div");
    bar.className = "scroll-progress";
    document.body.appendChild(bar);

    window.addEventListener(
        "scroll",
        () => {
            const h = document.documentElement;
            const scrolled = h.scrollTop / (h.scrollHeight - h.clientHeight);
            bar.style.width = `${scrolled * 100}%`;
        },
        { passive: true },
    );
}
