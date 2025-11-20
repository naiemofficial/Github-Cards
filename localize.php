<?php

// Enque Scripts
function github_card_enqueue_scripts() {
    wp_enqueue_script( 'github-card-js', plugin_dir_url(__FILE__) . 'assets/js/stats.js', ['jquery'], '1.0', true );
    wp_enqueue_script('github-card', plugin_dir_url(__FILE__) . 'assets/js/script.js', [], '1.0', true );
    wp_localize_script( 'github-card-js', 'githubCard', ['ajaxurl' => admin_url('admin-ajax.php')] );
}
add_action('wp_enqueue_scripts', 'github_card_enqueue_scripts');


// Enque Styles
function github_card_enqueue_styles() {
    wp_enqueue_style( 'github-card-css',  plugin_dir_url(__FILE__) . 'assets/css/styles.css', [], '1.0' );
    wp_enqueue_style('font-alliance-no-1', plugin_dir_url(__FILE__) . 'assets/css/fonts.css', [], '1.0' );

    // Font Awesome
    if(!wp_style_is('font-awesome', 'enqueued')){
        wp_dequeue_style('font-awesome');
        wp_deregister_style('font-awesome');
    }
    wp_enqueue_style( 'font-awesome', plugin_dir_url(__FILE__) . 'libs/fontawesome/css/all.min.css', [], '7.1.0' );
}
add_action('wp_enqueue_scripts', 'github_card_enqueue_styles');