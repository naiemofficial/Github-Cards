<?php
/** Register shortcode for GitHub profile data */


function github_user_cached($atts, $shortcode = false){
    if (!isset($atts['username']) || empty($atts['username'])) {
        return "Missing username attribute";
    }

    // Get user data
    $user_data = get_github_user_data_cached($atts['username']);
    
    // Check errors
    if(!$user_data) return 'Error: empty data - (Ref: S>U)';
    if($user_data instanceof WP_Error) return json_encode($user_data);



    $get = $atts['get'] ?? '';
    if(empty($get)) {
        return $shortcode ? json_encode($user_data) : $user_data;
    }

    // return $user_data[$get] ?? '';
    return isset($user_data[$get]) ? $user_data[$get] : "Key '{$get}' not found. (Invalid key or invalid username or private account) See available keys at https://docs.github.com/en/rest/users/users#get-a-user";
}


function fn_github_user($atts){
    return github_user_cached($atts, true);
}


add_shortcode('github_user', 'fn_github_user');