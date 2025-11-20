<?php

function get_github_stats($repo_full) {
    // Build API endpoints
    $repo_api           = "https://api.github.com/repos/{$repo_full}";
    $contributors_api   = "https://api.github.com/repos/{$repo_full}/contributors";
    $languages_api      = "https://api.github.com/repos/{$repo_full}/languages";
    $issues_api         = "https://api.github.com/repos/{$repo_full}/issues?state=all&per_page=1"; 

    // Fetch repo basic info
    $repo_data = github_safe_get($repo_api);
    
    // If error, don't process data; just return
    if (is_wp_error($repo_data)) return $repo_data;


    // ----------- START - Porcess data -----------
    // --------------------------
    // Contributors
    // --------------------------
    $contributors_data = github_safe_get($contributors_api);
    if(is_wp_error($contributors_data)) return $contributors_data;

    $contributors_total = (!is_wp_error($contributors_data) && is_array($contributors_data))
        ? count($contributors_data)
        : 0;

    // --------------------------
    // Count ALL issues (open + closed)
    // --------------------------
    $issues_total = 0;
    $issues_response = wp_remote_get($issues_api);

    if(is_wp_error($issues_response)) return $issues_response;

    if (!is_wp_error($issues_response)) {
        $issues_code = wp_remote_retrieve_response_code($issues_response);

        if ($issues_code === 200) {
            $headers = wp_remote_retrieve_headers($issues_response);

            if (isset($headers['link']) && preg_match('/page=(\d+)>; rel="last"/', $headers['link'], $match)) {
                $issues_total = intval($match[1]); // Correct total count from GitHub pagination
            } else {
                // fallback if only one page
                $body = json_decode(wp_remote_retrieve_body($issues_response), true);
                $issues_total = is_array($body) ? count($body) : 0;
            }
        }
    }

    // --------------------------
    // Programming languages (%)
    // --------------------------
    $languages_data = github_safe_get($languages_api);
    $languages = [];

    if (!is_wp_error($languages_data) && is_array($languages_data)) {
        $total_bytes = array_sum($languages_data);

        $linguist_data = get_github_linguist_cached([]);

        if ($total_bytes > 0) {
            foreach ($languages_data as $lang => $bytes) {
                $percentage = round(($bytes / $total_bytes) * 100, 1);
                $languages[$lang] = [
                    'percentage' => $percentage,
                    'bytes' => $bytes,
                    'color' => $linguist_data[$lang] ?? null,
                ];
            }
        }
    }

    // --------------------------
    // Final return array
    // --------------------------
    $stats = [
        'stars'        => $repo_data['stargazers_count'] ?? 0,
        'forks'        => $repo_data['forks_count'] ?? 0,
        'open_issues'  => $repo_data['open_issues_count'] ?? 0,
        'all_issues'   => $issues_total,
        'contributors' => $contributors_total,
        'languages'    => $languages,
    ];
    // ----------- END - Porcess data -----------

    return $stats;
}



function load_github_stats($repo_full) {
    return get_github_stats($repo_full);
}