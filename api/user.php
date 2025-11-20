<?php 

function get_github_user_data($username){
    $api = "https://api.github.com/users/{$username}";

    // Fetch user data
    $response = wp_remote_get($api);

    // If error, don't process data; just return
    if (is_wp_error($response)) return $response;

    

    // ----------- START/END - Porcess data -----------
    return json_decode(wp_remote_retrieve_body($response), true);
}


function load_get_github_user_data($username) {
    return get_github_user_data($username);
}