/**
 * Attaches a passive scroll listener to toggle header states.
 * @param {number} threshold - Scroll Y offset trigger in pixels.
 * @param {function(boolean): void} callback - Callback receives true when scrolled past threshold.
 * @returns {function(): void} Cleanup function to remove listener.
 */
export function listenHeaderScroll(threshold = 20, callback) {
    let isScrolled = false;

    const handleScroll = () => {
        const shouldBeScrolled = window.scrollY > threshold;
        if (shouldBeScrolled !== isScrolled) {
            isScrolled = shouldBeScrolled;
            callback(isScrolled);
        }
    };

    window.addEventListener("scroll", handleScroll, { passive: true });
    handleScroll(); // Run immediately on page load

    return () => window.removeEventListener("scroll", handleScroll);
}
