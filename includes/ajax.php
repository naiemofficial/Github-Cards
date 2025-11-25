<?php
// ------------------ START - Admin AJAX Handlers -----------------
function github_card_save_settings_ajax(){
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    check_ajax_referer('github_card_save_settings', 'nonce');
    parse_str($_POST['data'], $form_data);
    foreach ($form_data as $key => $value) {
        if (strpos($key, 'github_card_') === 0) {
            update_option($key, sanitize_text_field($value));
        }
    }
    wp_send_json_success('Settings saved');
}
add_action('wp_ajax_github_card_save_settings', 'github_card_save_settings_ajax');





function github_card_reset_settings_ajax(){
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized');
    }

    check_ajax_referer('github_card_save_settings', 'nonce');
    parse_str($_POST['data'], $form_data);

    foreach ($form_data as $key => $value) {
        if (strpos($key, 'github_card_') === 0) {
            delete_option($key);
        }
    }

    wp_send_json_success('Settings reset');
}
add_action('wp_ajax_github_card_reset_settings', 'github_card_reset_settings_ajax');

// ------------------ END - Admin AJAX Handlers -----------------









// AJAX handler for fetching GitHub stats
function github_card_fetch_github_repo_data(){
    // Check nonce for security
    if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'github_card_repo_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
    }

    $repo = sanitize_text_field($_GET['repo'] ?? '');
    if (empty($repo)) {
        wp_send_json_error(['message' => 'No repo specified']);
    }


    $repo_data = full_github_repo_data(['repo' => $repo]);

    if (is_wp_error($repo_data)) {
        wp_send_json_error(['message' => $repo_data]);
    }

    return wp_send_json_success($repo_data);
}
add_action('wp_ajax_fetch_github_repo_data', 'github_card_fetch_github_repo_data');
add_action('wp_ajax_nopriv_fetch_github_repo_data', 'github_card_fetch_github_repo_data');
// ------------------ END - AJAX handler -----------------
