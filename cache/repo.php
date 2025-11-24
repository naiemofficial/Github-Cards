<?php


require_once plugin_dir_path(__FILE__).'../api/repo.php';




// ------------------ START - AJAX handler -----------------
add_action('wp_ajax_fetch_github_repo_data', 'fetch_github_repo_data_ajax');
add_action('wp_ajax_nopriv_fetch_github_repo_data', 'fetch_github_repo_data_ajax');

function fetch_github_repo_data_ajax() {
    $repo = sanitize_text_field($_GET['repo'] ?? '');
    if (!$repo) wp_send_json_error('Missing repo');

    $repo_data = get_github_repo_data_cached($repo);
    if ($repo_data instanceof WP_Error) wp_send_json_error($repo_data->get_error_message());

    wp_send_json_success($repo_data);
}
// ------------------ END - AJAX handler -----------------








// Get GitHub repo repo_data with caching
function get_github_repo_data_cached($repo_full) {
    $transient_key = 'github_repo_data_' . md5($repo_full);

    // Check if cached
    $cached_repo_data = get_transient($transient_key);
    if ($cached_repo_data !== false) {
        return $cached_repo_data;
    }

    // Load from API
    $repo_data = load_github_repo_data($repo_full);

    // Don't cache if any error
    if ($repo_data instanceof WP_Error) return $repo_data;

    // Cache for 6 hour
    set_transient($transient_key, $repo_data, 6 * HOUR_IN_SECONDS);

    return $repo_data;
}