<?php

/**
 * Enqueue admin CSS
 */
function github_card_admin_style($hook){
    if ($hook !== 'toplevel_page_github-card-settings') return;

    wp_enqueue_style(
        'github-card-admin-style',
        plugin_dir_url(__DIR__) . '/admin/assets/css/styles.css',
        [],
        '1.0'
    );

    // Enque Tailwind CSS
    wp_enqueue_style(
        'tailwind',
        plugin_dir_url(__DIR__) . '/admin/assets/css/tailwind.min.css',
        [],
        '2.2.19'
    );


    // Enque font Only load in admin
    if (is_admin() && isset($_GET['page']) && $_GET['page'] === 'github-card-settings') {
        wp_enqueue_style(
            'font-alliance-no-1',
            plugin_dir_url(__FILE__) . 'assets/css/fonts.css',
            [],
            '1.0'
        );
    }

}
add_action('admin_enqueue_scripts', 'github_card_admin_style');