export function initMagnetic(selector = ".btn-cta, .nav-box") {
    if (window.matchMedia("(pointer: coarse)").matches) return; // skip touch

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
