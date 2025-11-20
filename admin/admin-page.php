<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Register settings
 */
function github_card_register_settings() {

    register_setting('github_card_settings_group', 'github_card_load_with');
    register_setting('github_card_settings_group', 'github_card_preloader_type');
    register_setting('github_card_settings_group', 'github_card_wrapper_preloader');
    register_setting('github_card_settings_group', 'github_card_counts_preloader');
    register_setting('github_card_settings_group', 'github_card_auto_scale');
    register_setting('github_card_settings_group', 'github_card_cache_enabled');
    register_setting('github_card_settings_group', 'github_card_cache_duration');
}
add_action('admin_init', 'github_card_register_settings');


/**
 * Add menu page
 */
function github_card_add_menu_page() {

    $icon_url = plugin_dir_url(__DIR__) . '/admin/assets/icons/github-card.svg';

    add_menu_page(
        'Github Card',
        'Github Card',
        'manage_options',
        'github-card-settings',
        'github_card_render_admin_page',
        $icon_url,
        56
    );
}
add_action('admin_menu', 'github_card_add_menu_page');





/**
 * Render admin page
 */
function github_card_render_admin_page() {
    $load_with = get_option('github_card_load_with', 'php');
    ?>

    <div class="github-card-admin wrap">
        <h1>Github Card Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields('github_card_settings_group'); ?>

            <div class="gc-section">
                <h2>Card Settings</h2>

                <label class="gc-label">Load with</label>
                <div class="gc-radio-row">
                    <label><input type="radio" name="github_card_load_with" value="php" <?php checked($load_with, 'php'); ?>> PHP Default</label>
                    <label><input type="radio" name="github_card_load_with" value="ajax" <?php checked($load_with, 'ajax'); ?>> jQuery / Ajax</label>
                </div>

                <div class="gc-conditional" data-condition="ajax">
                    <label class="gc-label">Preloader Type</label>
                    <select name="github_card_preloader_type">
                        <option value="skeleton">Skeleton</option>
                        <option value="spin">Spin</option>
                    </select>

                    <label class="gc-label">Wrapper Preloader</label>
                    <label class="gc-switch">
                        <input type="checkbox" name="github_card_wrapper_preloader" value="on" <?php checked(get_option('github_card_wrapper_preloader'), 'on'); ?>>
                        <span></span>
                    </label>

                    <label class="gc-label">Repo Counts Preloader</label>
                    <label class="gc-switch">
                        <input type="checkbox" name="github_card_counts_preloader" value="on" <?php checked(get_option('github_card_counts_preloader'), 'on'); ?>>
                        <span></span>
                    </label>
                </div>

                <label class="gc-label">Enable Auto Scale</label>
                <label class="gc-switch">
                    <input type="checkbox" name="github_card_auto_scale" value="on" <?php checked(get_option('github_card_auto_scale'), 'on'); ?>>
                    <span></span>
                </label>
            </div>


            <div class="gc-section">
                <h2>Cache Setting</h2>

                <label class="gc-label">Cache</label>
                <select name="github_card_cache_enabled">
                    <option value="enable" <?php selected(get_option('github_card_cache_enabled'), 'enable'); ?>>Enable</option>
                    <option value="disable" <?php selected(get_option('github_card_cache_enabled'), 'disable'); ?>>Disable</option>
                </select>

                <label class="gc-label">Duration (seconds)</label>
                <input type="number" name="github_card_cache_duration" value="<?php echo esc_attr(get_option('github_card_cache_duration', 3600)); ?>" min="1">
            </div>

            <?php submit_button(); ?>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        function updateVisibility() {
            const isAjax = document.querySelector("input[name='github_card_load_with']:checked").value === "ajax";
            document.querySelectorAll(".gc-conditional").forEach(el => {
                el.style.display = isAjax ? "block" : "none";
            });
        }

        document.querySelectorAll("input[name='github_card_load_with']").forEach(r => {
            r.addEventListener("change", updateVisibility);
        });

        updateVisibility();
    });
    </script>

<?php
}
