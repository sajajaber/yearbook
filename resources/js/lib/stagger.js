export function initStagger() {
    const groups = document.querySelectorAll(".stagger-group");
    const singles = document.querySelectorAll("[data-fade], .section-header");

    const groupObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const items = entry.target.querySelectorAll(".stagger-item");
                items.forEach((item, i) => {
                    item.style.transitionDelay = `${i * 80}ms`;
                    requestAnimationFrame(() =>
                        item.classList.add("is-visible"),
                    );
                });
                groupObserver.unobserve(entry.target);
            });
        },
        { threshold: 0.15 },
    );

    const singleObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-visible");
                    singleObserver.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.2 },
    );

    groups.forEach((g) => groupObserver.observe(g));
    singles.forEach((s) => singleObserver.observe(s));
}
