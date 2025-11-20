<?php

/**
 * Enqueue admin CSS
 */
function github_card_admin_style($hook)
{
    if ($hook !== 'toplevel_page_github-card-settings') return;

    wp_enqueue_style(
        'github-card-admin-style',
        plugin_dir_url(__DIR__) . '/admin/assets/css/styles.css',
        [],
        '1.0'
    );
}
add_action('admin_enqueue_scripts', 'github_card_admin_style');