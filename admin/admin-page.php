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
function github_card_render_admin_page()
{
    global $all_input_settings, $defaults;

    $load_with = get_option('github_card_load_with', 'php');
?>

    <div class="github-card-admin wrap max-w-4xl mx-auto py-8 animate-fade-in">

        <h1 class="github-card-admin-heading">Github Card Settings</h1>
        <div class="github-card-banner-wrapper">
            <img src="<?php echo plugin_dir_url(__DIR__); ?>admin/assets/icons/github-card.svg" alt="Github Card Banner" class="admin-settings-banner" />
        </div>
        <form method="post" action="options.php" class="space-y-10">
            <?php settings_fields('github_card_settings_group'); ?>

            <!-- SECTION: Card Settings -->
            <div class="bg-white shadow border rounded-lg p-6 space-y-6 animate-fade-in-up">
                <h2 class="text-xl font-semibold border-b pb-2">Card Settings</h2>

                <!-- Load With -->
                <?php
                $key = 'github_card_load_with';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $load_with = get_option($key, $input['default']);
                $values = $input['values'];
                ?>
                <div class="animate-fade-in-up">
                    <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                    <div class="flex gap-6">
                        <?php foreach ($values as $value_key => $text) { ?>
                            <label class="flex items-center gap-2 cursor-pointer animate-fade-in">
                                <input
                                    type="radio"
                                    name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value_key); ?>"
                                    class="h-4 w-4 text-[#141414] border-[#141414] checked:bg-[#141414] checked:border-[#141414]"
                                    <?php checked($load_with, $value_key); ?>>
                                <span><?php echo esc_html($text); ?></span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <!-- Conditional Section -->
                <div class="space-y-6 animate-fade-in-up">
                    <div class="space-y-6 gc-conditional" data-condition="js">
                        <!-- Preloader Type -->
                        <?php
                            $key = 'github_card_preloader_type';
                            $input = $all_input_settings[$key];
                            $label = $input['label'];
                            $preloader_type = get_option($key, $input['default']);
                            $values = $input['values'];
                        ?>
                        <div class="animate-fade-in-up">
                            <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                            <select
                                name="github_card_preloader_type"
                                class="border rounded-lg px-3 py-2 w-48 focus:ring-[#141414] focus:border-[#141414]">
                                <?php foreach ($values as $value) { ?>
                                    <option value="<?php echo esc_attr($value); ?>" <?php selected($preloader_type, $value); ?>>
                                        <?php echo esc_html(ucfirst($value)); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Switches -->
                        <?php
                        $switch_keys = ['github_card_wrapper_preloader', 'github_card_counts_preloader'];
                        foreach ($switch_keys as $key) {
                            $input = $all_input_settings[$key];
                            $label = $input['label'];
                            $value = get_option($key, $input['default']);
                        ?>
                            <div class="flex items-center justify-between animate-fade-in-up">
                                <label class="font-medium"><?php echo esc_html($label); ?></label>
                                <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value, 'on'); ?> />
                                    <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                </label>
                            </div>
                        <?php } ?>
                    </div>



                    <!-- Auto Scale -->
                    <?php
                        $key = 'github_card_auto_scale';
                        $input = $all_input_settings[$key];
                        $label = $input['label'];
                        $value = get_option($key, $input['default']);
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value, 'on'); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Cache Section -->
            <div class="bg-white shadow border rounded-lg p-6 space-y-6 animate-fade-in-up">
                <h2 class="text-xl font-semibold border-b pb-2">Cache Settings</h2>

                <?php
                $key = 'github_card_cache_enabled';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $cache_enabled = get_option($key, $input['default']);
                ?>
                <div class="flex items-center justify-between animate-fade-in">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <label class="relative inline-flex items-center cursor-pointer checkbox-label">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($cache_enabled, 'on'); ?> />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                </div>

                <?php
                $key = 'github_card_cache_duration';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                ?>
                <div class="animate-fade-in-up">
                    <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                    <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr(get_option($key, $input['default'])); ?>" min="1" class="border rounded-lg px-3 py-2 w-32 focus:ring-[#141414] focus:border-[#141414]">
                </div>
            </div>

            <button type="submit" class="save-github-card-settings">Save Settings</button>
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

<?php }
