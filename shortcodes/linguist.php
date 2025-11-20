<?php 

function fn_github_linguist($atts){
    $linguist_colors = get_github_linguist_cached($atts);
    if(!$linguist_colors) return 'Error: empty data - (Ref: S>L)';
    if($linguist_colors instanceof WP_Error) return json_encode($linguist_colors);

    return json_encode($linguist_colors);
}

add_shortcode('github_linguist', 'fn_github_linguist');