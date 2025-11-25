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
    register_setting('github_card_settings_group', 'github_card_data_preloader');
    register_setting('github_card_settings_group', 'github_card_auto_scale');
    register_setting('github_card_settings_group', 'github_card_cache_enabled');
    register_setting('github_card_settings_group', 'github_card_cache_duration');
}
add_action('admin_init', 'github_card_register_settings');


// Add body class for admin page
function github_card_admin_body_class($classes)
{
    $screen = get_current_screen();
    if ($screen && $screen->id === 'toplevel_page_github-card-settings') {
        $classes .= ' github-card-admin-page';
    }
    return $classes;
}
add_filter('admin_body_class', 'github_card_admin_body_class');










/**
 * Add menu page
 */
function github_card_add_menu_page(){
    // Correct SVG handling for admin menu icon
    $icon_path = plugin_dir_path(__DIR__) . 'admin/assets/icons/square-github.svg';
    $svg       = file_get_contents($icon_path);

    // Base64 encode the SVG for proper WordPress scaling
    $icon_url  = 'data:image/svg+xml;base64,' . base64_encode($svg);

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
?>

    <div class="github-card-admin wrap max-w-4xl mx-auto py-8 animate-fade-in">

        <h1 class="github-card-admin-heading">Github Card Settings</h1>
        <div class="github-card-banner-wrapper">
            <img src="<?php echo plugin_dir_url(__DIR__); ?>admin/assets/banners/github-card.svg" alt="Github Card Banner" class="admin-settings-banner" />
        </div>
        <form method="post" action="options.php" class="space-y-10">
            <?php settings_fields('github_card_settings_group'); ?>

            <!-- SECTION: Card Settings -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class=" text-xl font-semibold border-b pb-2">Card Settings</h2>

                <!-- Load With -->
                <?php
                $key = 'github_card_load_with';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $load_with = github_card_load_with();
                $values = $input['values'];
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
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
                    <div class="space-y-6" data-condition="js">
                        <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200" data-condition="data-preloader-off">
                            <!-- Wrapper Preloader  -->
                            <?php
                            $key = 'github_card_wrapper_preloader';
                            $input = $all_input_settings[$key];
                            $label = $input['label'];
                            $value = github_card_wrapper_preloader();
                            ?>
                            <div class="flex items-center justify-between animate-fade-in-up">
                                <label class="font-medium"><?php echo esc_html($label); ?></label>
                                <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                                    <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                </label>
                            </div>



                            <!-- Preloader Type -->
                            <?php
                            $key = 'github_card_preloader_type';
                            $input = $all_input_settings[$key];
                            $label = $input['label'];
                            $preloader_type = get_option($key, $defaults[$key]);
                            $values = $input['values'];
                            ?>
                            <div class="animate-fade-in-up flex items-center justify-between animate-scale-up" data-condition="wrapper-preloader-on">
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
                        </div>


                        <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200" data-condition="wrapper-preloader-off">
                            <!-- Data Preloader  -->
                            <?php
                            $key = 'github_card_data_preloader';
                            $input = $all_input_settings[$key];
                            $label = $input['label'];
                            $value = github_card_data_preloader();
                            ?>
                            <div class="flex items-center justify-between animate-fade-in-up">
                                <label class="font-medium"><?php echo esc_html($label); ?></label>
                                <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                                    <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                                    <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                                    <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Auto Scale -->
                <?php
                $key = 'github_card_auto_scale';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $value = github_card_auto_scale();
                ?>
                <div class="p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <label class="relative inline-flex items-center cursor-pointe checkbox-label m-0">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                </div>



                <!-- Spinner -->
                <?php
                $key = 'github_card_spinner';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $spinner = github_card_spinner();
                $values = $input['values'];
                ?>
                <div class="github_card_spinner p-4 rounded-lg border border-white flex items-center justify-between animate-fade-in-up">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <div class="flex gap-6 options">
                        <?php foreach ($values as $value) { ?>
                            <label class="flex items-center gap-2 cursor-pointer animate-fade-in">
                                <input
                                    type="radio"
                                    name="<?php echo esc_attr($key); ?>"
                                    value="<?php echo esc_attr($value); ?>"
                                    <?php checked($spinner, $value); ?>>
                                <span class="spinner-selection">
                                    <i class="fa-solid <?php echo esc_attr($value); ?> fa-spin"></i>
                                </span>
                            </label>
                        <?php } ?>
                    </div>
                </div>

                <div class="flex flex-col space-y-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <!-- Footer Ribbon  -->
                    <?php
                    $key = 'github_card_footer_ribbon';
                    $input = $all_input_settings[$key];
                    $label = $input['label'];
                    $value = github_card_footer_ribbon();
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up">
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>


                    <!-- Footer Ribbon  -->
                    <?php
                    $key = 'github_card_language_ribbon';
                    $input = $all_input_settings[$key];
                    $label = $input['label'];
                    $value = github_card_language_ribbon();
                    ?>
                    <div class="flex items-center justify-between animate-fade-in-up" data-condition="data-footer-ribbon-on">
                        <label class="font-medium"><?php echo esc_html($label); ?></label>
                        <label class="relative inline-flex items-center cursor-pointe checkbox-label">
                            <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($value); ?> />
                            <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                            <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                        </label>
                    </div>
                </div>

            </div>

            <!-- Cache Section -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-semibold border-b pb-2">Cache Settings</h2>

                <?php
                $key = 'github_card_cache_enabled';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $cache_enabled = github_card_cache_enabled();
                ?>
                <div class="flex items-center justify-between animate-fade-in">
                    <label class="font-medium"><?php echo esc_html($label); ?></label>
                    <label class="relative inline-flex items-center cursor-pointer checkbox-label">
                        <input type="checkbox" name="<?php echo esc_attr($key); ?>" value="on" class="sr-only peer" <?php checked($cache_enabled); ?> />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300 peer-checked"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                </div>

                <?php
                $key = 'github_card_cache_duration';
                $input = $all_input_settings[$key];
                $label = $input['label'];
                $placeholder = $input['placeholder'] ?? '';
                $cache_duration = get_option($key, $defaults[$key]);
                ?>
                <div class="flex items-center justify-between animate-fade-in" data-condition="cache-enabled-on">
                    <label class="block font-medium mb-2"><?php echo esc_html($label); ?></label>
                    <input type="number" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($cache_duration); ?>" min="1" placeholder="<?php echo $placeholder; ?>" class="border rounded-lg px-3 py-2 w-32 focus:ring-[#141414] focus:border-[#141414]">
                </div>
            </div>



            <div class="github-card-admin-settings submit-button-wrapper flex space-x-4 animate-fade-in-up justify-between">
                <button
                    type="button" id="github_card_save_settings"
                    class="github-card-admin-button save-github-card-settings"
                    data-loading="<?php esc_attr_e('Saving...'); ?>"
                    data-success="<?php esc_attr_e('Saved!'); ?>">
                    <i class="fa-solid fa-floppy-disk gc-icon-default"></i>
                    <i class="fa-solid fa-spinner fa-spin gc-icon-loading" style="display:none;"></i>
                    <i class="fa-solid fa-circle-check gc-icon-success" style="display:none; color:#2ecc71;"></i>
                    <span class="gc-btn-text">Save Settings</span>
                </button>

                <div class="reset-button-wrapper flex items-center space-x-2">

                    <label class="relative inline-flex items-center cursor-pointer checkbox-label" style="transform: scale(0.8)">
                        <input type="checkbox" id="gc-reset-confirm-checkbox" class="sr-only peer" />
                        <div class="w-10 h-6 bg-white border border-gray-300 rounded-full transition-colors duration-300" bis_skin_checked="1"></div>
                        <span class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform duration-300 peer-checked"></span>
                    </label>
                    <button type="reset" id="github_card_reset_settings"
                        class="github-card-admin-button reset-github-card-settings"
                        data-loading="<?php esc_attr_e('Resetting...'); ?>"
                        data-success="<?php esc_attr_e('Reset!'); ?>"
                        disabled>
                        <i class="fa-solid fa-rotate-left gc-icon-default"></i>
                        <i class="fa-solid fa-spinner fa-spin gc-icon-loading" style="display:none;"></i>
                        <i class="fa-solid fa-circle-check gc-icon-success" style="display:none; color:#2ecc71;"></i>
                        <span class="gc-btn-text">Reset</span>
                    </button>
                </div>
            </div>

        </form>
    </div>

<?php }
