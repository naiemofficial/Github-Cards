/* document.addEventListener("DOMContentLoaded", function() {
    const wrapper = document.querySelector(".github-card-wrapper");
    const card  = wrapper.querySelector(".github-card");

    const resize = () => {
        const scale = wrapper.clientWidth / 1200;
        card.style.transform = `scale(${scale})`;
        wrapper.style.height = `${600 * scale}px`; // keep aspect ratio
    };

    resize();
    new ResizeObserver(resize).observe(wrapper);
}); */




(function () {
    const originalWidth = 1200;
    const originalHeight = 600;

    function scaleCard(wrapper) {
        const card = wrapper.querySelector(".github-card");
        if (!card) return;

        const scale = wrapper.clientWidth / originalWidth;

        card.style.transformOrigin = "top left";
        card.style.transform = `scale(${scale})`;

        wrapper.style.height = `${originalHeight * scale}px`;

        card.style.visibility = "visible";
    }

    function init(wrapper) {
        // avoid double initialization
        if (wrapper.dataset.scaled) return;
        wrapper.dataset.scaled = "1";

        // scale immediately
        scaleCard(wrapper);

        // update on resize
        new ResizeObserver(() => scaleCard(wrapper)).observe(wrapper);
    }

    // Watch entire DOM
    const mo = new MutationObserver(() => {
        console.log("MutationObserver triggered");
        document.querySelectorAll(".github-card-wrapper").forEach(init);
    });

    // Start observing the whole document
    mo.observe(document.documentElement, {
        childList: true,
        subtree: true
    });

    // Also check immediately (if already exists)
    document.querySelectorAll(".github-card-wrapper").forEach(init);
})();