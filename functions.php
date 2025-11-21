<?php

$all_input_settings = include plugin_dir_path(__FILE__) . 'includes/settings-input.php';
$defaults = array_map(function ($setting) {
    return $setting['default'];
}, $all_input_settings);

/**
 * Safely fetch a GitHub API endpoint.
 * Returns decoded JSON array or WP_Error on failure.
 */
function github_safe_get($url) {
    $response = wp_remote_get($url, [
        'headers' => [
            'User-Agent'    => 'WordPress/1.0',
            'Accept'        => 'application/vnd.github+json',
            'X-GitHub-Api-Version' => '2022-11-28'
        ],
        'timeout' => 15
    ]);

    // WordPress HTTP error
    if (is_wp_error($response)) return $response;

    
    $code = wp_remote_retrieve_response_code($response);

    

    // GitHub forbidden / rate limited
    if ($code === 403) {
        return new WP_Error('github_forbidden', 'GitHub API returned 403 Forbidden');
    }

    if ($code !== 200) {
        return new WP_Error('github_error', "GitHub API returned status: $code");
    }

    // JSON decode
    return json_decode(wp_remote_retrieve_body($response), true);
}




// ------------------ SATRT - Option Getters ---------------- //
function github_card_load_with() {
    global $defaults;
    $key = 'github_card_load_with';
    return get_option($key, $defaults[$key]);
}

function github_card_preloader_type() {
    global $defaults;
    $key = 'github_card_preloader_type';
    return get_option($key, $defaults[$key]);
}

function github_card_wrapper_preloader() {
    global $defaults;
    $key = 'github_card_wrapper_preloader';
    return get_option($key, $defaults[$key]);
}

function github_card_counts_preloader() {
    global $defaults;
    $key = 'github_card_counts_preloader';
    return get_option($key, $defaults[$key]);
}

function github_card_auto_scale() {
    global $defaults;
    $key = 'github_card_auto_scale';
    return get_option($key, $defaults[$key]);
}

function github_card_cache_enabled() {
    global $defaults;
    $key = 'github_card_cache_enabled';
    return get_option($key, $defaults[$key]);
}

function github_card_cache_duration() {
    global $defaults;
    $key = 'github_card_cache_duration';
    return get_option($key, $defaults[$key]);
}
// ------------------ END - Option Getters ---------------- //