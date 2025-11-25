document.addEventListener("DOMContentLoaded", function() {
    function updateVisibility() {
        // Load with
        const loadWith = document.querySelector("input[name='github_card_load_with']:checked").value;
        document.querySelectorAll("[data-condition='js']").forEach(el => {
            if (loadWith === "js") {
                el.classList.remove("hidden");
            } else {
                el.classList.add("hidden");
            }
        });

        // Wrapper Preloader
        const wrapperPreloaderCheckbox = document.querySelector("input[name='github_card_wrapper_preloader']");
        const wrapperPreloaderOn = wrapperPreloaderCheckbox.checked;
        document.querySelectorAll("[data-condition='wrapper-preloader-on']").forEach(el => {
            if (wrapperPreloaderOn) {
                el.classList.remove("hidden");
            } else {
                el.classList.add("hidden");
            }
        });
        document.querySelectorAll("[data-condition='wrapper-preloader-off']").forEach(el => {
            if (wrapperPreloaderOn) {
                el.classList.add("hidden");
            } else {
                el.classList.remove("hidden");
            }
        });

        // Data Preloader
        const dataPreloaderCheckbox = document.querySelector("input[name='github_card_data_preloader']");
        const dataPreloaderOn = dataPreloaderCheckbox.checked;
        document.querySelectorAll("[data-condition='data-preloader-on']").forEach(el => {
            if (dataPreloaderOn) {
                el.classList.remove("hidden");
            } else {
                el.classList.add("hidden");
            }
            
        });
        document.querySelectorAll("[data-condition='data-preloader-off']").forEach(el => {
            if (dataPreloaderOn) {
                el.classList.add("hidden");
            } else {
                el.classList.remove("hidden");
            }
        });



        // Cache Enabled
        const cacheEnabledCheckbox = document.querySelector("input[name='github_card_cache_enabled']");
        const cacheEnabledOn = cacheEnabledCheckbox.checked;
        document.querySelectorAll("[data-condition='cache-enabled-on']").forEach(el => {
            if (cacheEnabledOn) {
                el.classList.remove("hidden");
            } else {
                el.classList.add("hidden");
            }
        });




        // Footer Ribbon
        const footerRibbonCheckbox = document.querySelector("input[name='github_card_footer_ribbon']");
        const footerRibbonOn = footerRibbonCheckbox.checked;
        document.querySelectorAll("[data-condition='data-footer-ribbon-on']").forEach(el => {
            if (footerRibbonOn) {
                el.classList.remove("hidden");
            } else {
                el.classList.add("hidden");
            }
        });
        document.querySelectorAll("[data-condition='data-footer-ribbon-off']").forEach(el => {
            if (footerRibbonOn) {
                el.classList.add("hidden");
            } else {
                el.classList.remove("hidden");
            }
        });

    }
    document.querySelectorAll(".github-card-admin input, .github-card-admin select").forEach(input => {
        input.addEventListener("change", updateVisibility);
    });

    updateVisibility();
    


    // Reset Button Enable/Disable
    const resetCheckbox = document.getElementById("gc-reset-confirm-checkbox");
    const resetButton = document.getElementById("github_card_reset_settings");
    resetCheckbox.addEventListener("change", function() {
        resetButton.disabled = !this.checked;
    });
});




















// AJAX Save Settings
jQuery(document).ready(function ($) {

    $(".github-card-admin-settings button").on("click", function (e) {
        e.preventDefault();

        const $btn = $(this);
        const id = $btn.attr("id");

        const $save = $btn.find(".gc-icon-default");
        const $load = $btn.find(".gc-icon-loading");
        const $success = $btn.find(".gc-icon-success");
        const $text = $btn.find(".gc-btn-text");
        const defaultText = $text.text();

        // Step 1: Switch to loading
        $save.hide();
        $success.hide();
        $load.show();
        $text.text($btn.data("loading"));

        const formData = {
            action: id,
            nonce: githubCardAjax.nonce,
            data: $("form").serialize()
        };

        // Include unchecked checkboxes (jQuery serialize ignores them)
        $("input[type=checkbox]").each(function () {
            if (!this.checked) {
                formData.data += "&" + this.name + "=0";
            }
        });


        $.post(githubCardAjax.ajax_url, formData, function (response) {

            // Stop loading spinner
            $load.hide();

            if (response.success) {

                // Step 2: Show tick/checkmark
                $success.show();
                $text.text($btn.data("success"));
                if (id.indexOf("reset_settings") !== -1) {
                    $btn.prop("disabled", true);
                    $text.text("Redirecting...");
                }

                // Step 3: After 2 seconds return to default save icon
                setTimeout(() => {
                    if (id.indexOf("save_settings") !== -1) {
                        $success.hide();
                        $save.show();
                        $text.text(defaultText);
                    }

                    if (id.indexOf("reset_settings") !== -1) {
                        location.reload();
                    }
                }, 2000);

            } else {
                // Handle error
                $save.show();
                $text.text("Error!");
            }
        });
    });

});
