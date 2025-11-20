<?php

/**
 * Generic shortcode builder for a specific field.
 */
function github_stat_cached($atts, $field, $shortcode = false){
    $a = shortcode_atts([
        'repo' => '',
    ], $atts);

    if (empty($a['repo'])) return 'Missing repo=""';

    // Get repo stats
    $stats = get_github_stats_cached($a['repo']);

    // Check errors
    if (!$stats) return 'Error: empty data - (Ref: S>S)';
    if ($stats instanceof WP_Error) return json_encode($stats);



    // Special handling for languages
    if ($field === 'languages') {
        return github_languages_cached($atts, $stats, $shortcode);
    }

    // Default behavior for stars, forks, issues, contributors
    return isset($stats[$field]) ? $stats[$field] : '';
}


function github_languages_cached($atts, $stats, $shortcode = false){
    if(empty($stats['languages']))  return 'Languages not found';

    // Check if 'array' key exists and is truthy
    if (!empty($atts['array']))  return $stats['languages']; // return associative array

    // return as formatted string: "PHP 30%, HTML 20.3%"
    $text_formatted = [];
    $color_formatted = [];
    foreach ($stats['languages'] as $lang => $data) {
        $percentage = $data['percentage'];
        $color = $data['color'] ?? 'N/A';

        $text_formatted[] = "{$lang} {$percentage}%";
        $color_formatted[] = "{$color} {$percentage}%";
    }

    if(!empty($atts['colors']) && $atts['colors'] === 'true'){
        return implode(', ', $color_formatted);
    }
    return implode(', ', $text_formatted);
}











function fn_github_stat($atts, $field){
    return github_stat_cached($atts, $field, true);
}


/** Register shortcodes */
add_shortcode('github_stars_count', function($atts){
    return fn_github_stat($atts, 'stars');
});

add_shortcode('github_forks_count', function($atts){
    return fn_github_stat($atts, 'forks');
});

add_shortcode('github_issues_count', function($atts){
    return fn_github_stat($atts, 'all_issues');
});

add_shortcode('github_contributors_count', function($atts){
    return fn_github_stat($atts, 'contributors');
});

add_shortcode('github_languages', function($atts){
    return fn_github_stat($atts, 'languages');
});