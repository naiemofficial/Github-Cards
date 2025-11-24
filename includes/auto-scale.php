<style type="text/css">
    /* Scale */
    .github-card-wrapper {
        width: 100%;
        height: auto;
        position: relative;
        overflow: hidden;

        /* Set the max width to match original 1200px design */
        max-width: 1200px;
    }

    /* SCALE the card based on available wrapper width */
    .github-card-wrapper .github-card {
        transform-origin: top left;
    }

    @media (max-width: 1200px) {
        .github-card-wrapper .github-card {
            transform: scale(calc(min(1, 100vw / 1200px)));
            width: 1200px;
            /* keep original width for scale calculation */
            height: auto;
        }
    }
</style>

<script type="text/javascript">
    (function() {
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
            // console.log("MutationObserver triggered");
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
</script>