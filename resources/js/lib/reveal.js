function splitIntoWords(el) {
    const text = el.textContent.trim();
    el.textContent = "";
    const words = text.split(/\s+/);
    words.forEach((word, i) => {
        const wrap = document.createElement("span");
        wrap.style.display = "inline-block";
        wrap.style.overflow = "hidden";

        const span = document.createElement("span");
        span.className = "reveal-word";
        span.style.transitionDelay = `${i * 40}ms`;
        span.textContent = word;

        wrap.appendChild(span);
        el.appendChild(wrap);
        if (i < words.length - 1) el.appendChild(document.createTextNode(" "));
    });
}

export function initRevealText(selector = "[data-reveal]") {
    const targets = document.querySelectorAll(selector);
    targets.forEach(splitIntoWords);

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("is-revealed");
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.2 },
    );

    targets.forEach((el) => observer.observe(el));
}
