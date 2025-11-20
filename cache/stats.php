<?php


require_once plugin_dir_path(__FILE__) . '../api/stats.php';




// ------------------ START - AJAX handler -----------------
add_action('wp_ajax_fetch_github_stats', 'fetch_github_stats_ajax');
add_action('wp_ajax_nopriv_fetch_github_stats', 'fetch_github_stats_ajax');

function fetch_github_stats_ajax() {
    $repo = sanitize_text_field($_GET['repo'] ?? '');
    if (!$repo) wp_send_json_error('Missing repo');

    $stats = get_github_stats_cached($repo);
    if ($stats instanceof WP_Error) wp_send_json_error($stats->get_error_message());

    wp_send_json_success($stats);
}
// ------------------ END - AJAX handler -----------------








// Get GitHub repo stats with caching
function get_github_stats_cached($repo_full) {
    $transient_key = 'github_stats_' . md5($repo_full);

    // Check if cached
    $cached_stats = get_transient($transient_key);
    if ($cached_stats !== false) {
        // return $cached_stats;
    }

    // Load from API
    $stats = load_github_stats($repo_full);

    // Don't cache if any error
    if ($stats instanceof WP_Error) return $stats;

    // Cache for 6 hour
    set_transient($transient_key, $stats, 6 * HOUR_IN_SECONDS);

    return $stats;
}