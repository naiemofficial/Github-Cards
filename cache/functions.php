<?php


function github_card_clear_cache(){
    global $wpdb;
    $transient_name_like = 'github_card_%';
    $result = $wpdb->query("
        DELETE FROM $wpdb->options
        WHERE option_name LIKE '_transient_$transient_name_like'
        OR option_name LIKE '_transient_timeout_$transient_name_like'
    ");

    return $result;
}