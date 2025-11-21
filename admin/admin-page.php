<?php
// Exit if accessed directly
if (!defined('ABSPATH')) exit;

/**
 * Register settings
 */
function github_card_register_settings()
{

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
function github_card_add_menu_page()
{

    $icon_url = plugin_dir_url(__DIR__) . '/admin/assets/icon/github-card.svg';

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
function github_card_render_admin_page()
{
    global $all_input_settings, $defaults;

    $load_with = get_option('github_card_load_with', 'php');
?>

    <div class="github-card-admin wrap">
        <h1>Github Card Settings</h1>

        <form method="post" action="options.php">
            <?php settings_fields('github_card_settings_group'); ?>

            <div class="gc-section">
                <h2>Card Settings</h2>

                <?php // --------------- Load With ------------------
                $key = 'github_card_load_with';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $load_with = get_option($key, $input['default']);
                $values = $input['values'];
                ?>
                <label class="gc-label"><?php echo esc_html($label); ?></label>
                <div class="gc-radio-row">
                    <?php foreach ($values as $value_key => $text) { ?>
                        <label>
                            <input
                                type="radio"
                                name="<?php echo esc_attr($key); ?>"
                                value="<?php echo esc_attr($value_key); ?>"
                                <?php checked($load_with, $value_key); ?>>
                            <?php echo esc_html($text); ?>
                        </label>
                    <?php } ?>
                </div>








                <div class="gc-conditional" data-condition="js">
                    <?php // --------------- Preloader Type ------------------ 
                    $key = 'github_card_preloader_type';
                    $input = $all_input_settings[$key];
                    $label = $input['label'];
                    $preloader_type = get_option($key, $input['default']);
                    $values = $input['values'];
                    ?>
                    <label class="gc-label"><?php echo esc_html($label); ?></label>
                    <select name="github_card_preloader_type">
                        <?php foreach ($values as $value) { ?>
                            <option value="<?php echo esc_attr($value); ?>" <?php selected($preloader_type, $value); ?>>
                                <?php echo esc_html(ucfirst($value)); ?>
                            </option>
                        <?php } ?>
                    </select>



                    <?php // --------------- Wrapper Preloader ------------------ 
                    $key = 'github_card_wrapper_preloader';
                    $input = $all_input_settings[$key];
                    $label = $input['label'];
                    $wrapper_preloader = get_option($key, $input['default']);
                    ?>
                    <label class="gc-label">
                    <label class="gc-switch">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" <?php checked($wrapper_preloader, 'on'); ?> >
                        <span></span>
                    </label>
                    


                    <?php // --------------- Repo Counts Preloader ------------------
                    $key = 'github_card_counts_preloader';
                    $input = $all_input_settings[$key];
                    $label = $input['label'];
                    $counts_preloader = get_option($key, $input['default']);
                    ?>
                    <label class="gc-label"></label>
                    <label class="gc-switch">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" <?php checked($counts_preloader, 'on'); ?> >
                        <span></span>
                    </label>
                </div>
                



                <?php // --------------- Auto Scale ------------------
                $key = 'github_card_auto_scale';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $auto_scale = get_option($key, $input['default']);
                ?>
                <label class="gc-label"></label>
                <label class="gc-switch">
                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" <?php checked($auto_scale, 'on'); ?> >
                    <span></span>
                </label>
            </div>


            <div class="gc-section">
                <h2>Cache Setting</h2>

                <?php // --------------- Cache Enabled ------------------
                $key = 'github_card_cache_enabled';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $cache_enabled = get_option($key, $input['default']);
                ?>
                <label class="gc-label">
                <label class="gc-switch">
                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" <?php checked($cache_enabled, 'on'); ?> >
                    <span></span>
                </label>


                <?php // --------------- Cache Duration ------------------
                $key = 'github_card_cache_duration';
                $input = $all_input_settings[$key];
                $label = $input['label'];

                ?>
                <label class="gc-label"><?php echo esc_html($label); ?></label>
                <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_option($key, $input['default'])); ?>" min="1">
            </div>

            <?php submit_button(); ?>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            function updateVisibility() {
                const isAjax = document.querySelector("input[name='github_card_load_with']:checked").value === "js";
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
