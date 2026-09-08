export function initCounters(selector = "[data-counter]") {
    const els = document.querySelectorAll(selector);

    const animate = (el) => {
        const target = parseInt(el.textContent.replace(/\D/g, ""), 10) || 0;
        const suffix = el.textContent.replace(/[\d,]/g, "");
        const duration = 1400;
        const start = performance.now();

        const step = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3); // ease-out-cubic
            const value = Math.floor(eased * target);
            el.textContent = value.toLocaleString() + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    };

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    animate(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.5 },
    );

    els.forEach((el) => observer.observe(el));
}
