<?php

// Enqueue Styles and Scripts
function github_card_enqueue_styles_script(){

    // ----------------- STYLES ----------------- //
    wp_enqueue_style('github-card-css', plugin_dir_url(__FILE__) . 'assets/css/styles.css', [], '1.0');
    wp_enqueue_style('font-alliance-no-1', plugin_dir_url(__FILE__) . 'assets/css/fonts.css', [], '1.0');

    // Font Awesome
    if (wp_style_is('font-awesome', 'enqueued')) {
        wp_dequeue_style('font-awesome');
        wp_deregister_style('font-awesome');
    }
    wp_enqueue_style('font-awesome', plugin_dir_url(__FILE__) . 'libs/fontawesome/css/all.min.css', [], '7.1.0');

    // ----------------- SCRIPTS ----------------- //
    wp_enqueue_script('github-card-repo-script', plugin_dir_url(__FILE__) . 'assets/js/github-card.js', [], '1.0', true);


    // Localize data for AJAX
    if (github_card_load_with() === 'js') {
        $data = [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('github_card_repo_nonce'),
        ];

        wp_add_inline_script('github-card-repo-script', 'var githubCardRepo = ' . wp_json_encode($data) . ';');

        // Enqueue the dummy script after adding inline data
        wp_enqueue_script('github-card-repo-script');

        // Include your PHP JS code
        // include plugin_dir_path(__FILE__) . 'includes/load_with_js.php'; => merged to settings-scripts-styles.php
    }

    // Settings Script and Styles
    include plugin_dir_path(__FILE__) . 'includes/settings-scripts-styles.php';
}
add_action('wp_enqueue_scripts', 'github_card_enqueue_styles_script');
