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
function github_card_load_with($requested_option = '') {
    global $defaults;
    $key = 'github_card_load_with';
	$option = get_option($key, $defaults[$key]);
	if(!empty($requested_option)){
		return !empty($option) && $option === $requested_option;
    }
    return get_option($key, $defaults[$key]);
}

function github_card_wrapper_preloader() {
    if (github_card_load_with() === 'php') {
        return null;
    }
    
    global $defaults;
    $key = 'github_card_wrapper_preloader';
    $value = filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
    if ($value) {
        update_option('github_card_data_preloader', false);
    }
    return $value;
}

function github_card_preloader_type($requested_option = ''){
    if(in_array(github_card_wrapper_preloader(), [null, false], true)){
        return !empty($requested_option) ? false : null;
    }

    global $defaults;
    $key = 'github_card_preloader_type';
	$option = get_option($key, $defaults[$key]);
	if(!empty($requested_option)){
		return !empty($option) && $option === $requested_option;
    }
    return get_option($key, $defaults[$key]);
}

function github_card_data_preloader() {
    global $defaults;
    $key = 'github_card_data_preloader';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_auto_scale() {
    global $defaults;
    $key = 'github_card_auto_scale';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}


function github_card_spinner() {
    global $defaults;
    $key = 'github_card_spinner';
    return get_option($key, $defaults[$key]);
}

function github_card_footer_ribbon() {
    global $defaults;
    $key = 'github_card_footer_ribbon';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_language_ribbon() {
    if(!github_card_footer_ribbon()) {
        return false;
    }
    
    global $defaults;
    $key = 'github_card_language_ribbon';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}


function github_card_error() {
    global $defaults;
    $key = 'github_card_error';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_preloader_spinner_color() {
    global $defaults;
    $key = 'github_card_preloader_spinner_color';
    return get_option($key, $defaults[$key]);
}

function github_card_preloader_background_color() {
    global $defaults;
    $key = 'github_card_preloader_background_color';
    return get_option($key, $defaults[$key]);
}

function github_card_enable_preloader_blur() {
    global $defaults;
    $key = 'github_card_enable_preloader_blur';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_preloader_blur_px() {
    global $defaults;
    $key = 'github_card_preloader_blur_px';
    return get_option($key, $defaults[$key]);
}


function github_card_skeleton_primary_color() {
    global $defaults;
    $key = 'github_card_skeleton_primary_color';
    return get_option($key, $defaults[$key]);
}


function github_card_skeleton_secondary_color() {
    global $defaults;
    $key = 'github_card_skeleton_secondary_color';
    return get_option($key, $defaults[$key]);
}

function github_card_footer_ribbon_color() {
    global $defaults;
    $key = 'github_card_footer_ribbon_color';
    return get_option($key, $defaults[$key]);
}

function github_card_cache_enabled() {
    global $defaults;
    $key = 'github_card_cache_enabled';
    return filter_var(get_option($key, $defaults[$key]), FILTER_VALIDATE_BOOLEAN);
}

function github_card_cache_duration() {
    if(!github_card_cache_enabled()){
        return 0;
    }
    
    global $defaults;
    $key = 'github_card_cache_duration';
    return intval(get_option($key, $defaults[$key]));
}
// ------------------ END - Option Getters ---------------- //



function get_or_null($array, $key){
	return is_array($array) && isset($array[$key]) ? $array[$key] : null;
}