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

function sanitizeRichTextHtml(html) {
    const template = document.createElement("template");
    template.innerHTML = html || "";

    const allowed = new Set(["B", "STRONG", "I", "EM", "P", "BR"]);

    const cleanNode = (node) => {
        if (node.nodeType === Node.TEXT_NODE) {
            return document.createTextNode(node.nodeValue);
        }

        if (node.nodeType !== Node.ELEMENT_NODE) {
            return document.createDocumentFragment();
        }

        const tag = node.tagName.toUpperCase();
        if (!allowed.has(tag)) {
            const fragment = document.createDocumentFragment();
            node.childNodes.forEach((child) => fragment.appendChild(cleanNode(child)));
            return fragment;
        }

        const clean = document.createElement(tag.toLowerCase());
        node.childNodes.forEach((child) => clean.appendChild(cleanNode(child)));
        return clean;
    };

    const wrapper = document.createElement("div");
    template.content.childNodes.forEach((node) => wrapper.appendChild(cleanNode(node)));
    return wrapper.innerHTML;
}

function initRichTextDisplay() {
    document
        .querySelectorAll(".event-description p, .ceremony-description")
        .forEach((element) => {
            const encodedHtml = element.textContent || "";
            if (!/[<&][a-z!/]/i.test(encodedHtml)) {
                return;
            }

            element.innerHTML = sanitizeRichTextHtml(encodedHtml);
        });
}

export function initRevealText(selector = "[data-reveal]") {
    const targets = document.querySelectorAll(selector);
    targets.forEach(splitIntoWords);

    initRichTextDisplay();

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
