<?php


require_once plugin_dir_path(__FILE__) . '../api/user.php';



function get_github_user_data_cached($username) {
    $transient_key = 'github_user_' . md5($username);

    // Check if cached
    $cached_user = get_transient($transient_key);
    if ($cached_user !== false) {
        return $cached_user;
    }

    // Load from API
    $user_data = load_get_github_user_data($username);

    // Don't cache if any error
    if ($user_data instanceof WP_Error) return $user_data;

    // Cache for 6 hour
    set_transient($transient_key, $user_data, 6 * HOUR_IN_SECONDS);
    return $user_data;
}


