<?php


function fn_github_card_template($atts)
{
    ob_start();

    global $data_loading_icon, $counts_empty_placeholder, $default_avatar;


    $repo = isset($atts['repo']) ? esc_attr($atts['repo']) : '/';

    // split by slash to get username and reponame
    $exploded_repo = explode('/', trim($repo, '/'));
    $username = isset($exploded_repo[0]) ? $exploded_repo[0] : $repo;
    $reponame = isset($exploded_repo[1]) ? $exploded_repo[1] : '';
    $repolink = "https://github.com/" . $username . "/" . $reponame;


    // Shortcode Parameters
    $description_words = isset($atts['description-words']) ? intval($atts['description-words']) : -1;
    $show_description = !isset($atts['description']) || $atts['description'] !== 'false';
    $show_contributors = !isset($atts['contributors']) || $atts['contributors'] !== 'false';
    $show_issues = !isset($atts['issues']) || $atts['issues'] !== 'false';
    $show_stars = !isset($atts['stars']) || $atts['stars'] !== 'false';
    $show_forks = !isset($atts['forks']) || $atts['forks'] !== 'false';

    $paratmeters = [
        'description-words' => $description_words
    ];


    $error = false;
    $error_text = null;
    if (github_card_load_with() === 'php') {
        $repo_data = full_github_repo_data($atts);
        if (is_wp_error($repo_data)) {
            $error = true;
            $error_text = json_encode($repo_data);
        }
        $description = get_or_null($repo_data, 'description');

        $user = get_or_null($repo_data, 'user');
        $user_avatar_url = get_or_null($user, 'avatar_url');

        $contributors = get_or_null($repo_data, 'contributors');
        $issues = get_or_null($repo_data, 'all_issues');
        $stars = get_or_null($repo_data, 'stars');
        $forks = get_or_null($repo_data, 'forks');

        $color_gradient = get_or_null($repo_data, 'color_gradient');
    }
?>

    <div class="github-card-wrapper"
    <?php if (isset($atts['repo']) && !empty($atts['repo'])): ?>
        data-github-repo="<?php echo esc_attr($atts['repo']); ?>"
    <?php endif; ?>
    data-parameters='<?php echo json_encode($paratmeters); ?>
    '>
        <div class="github-card">
            <?php if(github_card_wrapper_preloader()){ ?>
                <div class="github-card-wrapper-preloader">
                    <?php echo $data_loading_icon; ?>
                </div>
            <?php } ?>
            <div class="github-card-header">
                <div class="github-card-title">
                    <a href="<?php echo $repolink; ?>" target="_blank" rel="noopener noreferrer">
                        <h3><?php echo $username; ?>/<strong><?php echo $reponame; ?></strong></h3>
                    </a>
                    <?php if ($show_description): ?>
                        <?php if ($error) { ?>
                            <p class="error"><?php echo $error_text; ?></p>
                        <?php } else { ?>
                            <p data-repo-description>
                                <?php if (github_card_load_with() === 'php') { ?>
                                    <?php if ($description_words < 0) {
                                        echo $description;
                                    } elseif ($description_words == 0) {
                                        echo '';
                                    } else {
                                        echo wp_trim_words($description, $description_words);
                                    } ?>
                                <?php } else if (github_card_load_with() === 'js') { ?>
                                    <?php if (github_card_data_preloader()) {
                                        echo $data_loading_icon;
                                    } else {
                                    } ?>
                                <?php } ?>
                            </p>
                        <?php } ?>
                    <?php endif; ?>
                </div>
                <div class="github-card-user">
                    <div class="github-card-avatar" data-repo-avatar>
                        <?php if (github_card_load_with() === 'php') { ?>
                            <img src="<?php echo $error ? $default_avatar : $user_avatar_url; ?>" alt="<?php echo $username; ?>" />
                        <?php } else if (github_card_load_with() === 'js') { ?>
                            <img src="<?php echo $default_avatar; ?>" alt="<?php echo $username; ?>" />

                            <?php if (github_card_data_preloader()): ?>
                                <span class="avatar-preloader">
                                    <?php echo $data_loading_icon; ?>
                                </span>
                            <?php endif; ?>
                        <?php } ?>

                    </div>
                </div>
            </div>



            <!-- Stats and Counts -->
            <div class="github-card-footer">
                <div class="github-card-footer-wrapper">
                    <div class="github-card-stats">
                        <!-- Contributors -->
                        <?php if ($show_contributors): ?>
                            <div class="github-card-stat">
                                <i class="far fa-users"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-contributors>
                                        <?php if (github_card_load_with() === 'php') { ?>
                                            <?php echo $contributors; ?>
                                        <?php } else if (github_card_load_with() === 'js') { ?>
                                            <?php echo github_card_data_preloader() ? $data_loading_icon : $counts_empty_placeholder; ?>
                                        <?php } ?>
                                    </span>
                                    <span>Contributors</span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Issues -->
                        <?php if ($show_issues): ?>
                            <div class="github-card-stat">
                                <i class="far fa-dot-circle"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-issues>
                                        <?php if (github_card_load_with() === 'php') { ?>
                                            <?php echo $issues; ?>
                                        <?php } else if (github_card_load_with() === 'js') { ?>
                                            <?php echo github_card_data_preloader() ? $data_loading_icon : $counts_empty_placeholder; ?>
                                        <?php } ?>
                                    </span>
                                    <span>Issues</span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Stars -->
                        <?php if ($show_stars): ?>
                            <div class="github-card-stat ml-12">
                                <i class="far fa-star"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-stars>
                                        <?php if (github_card_load_with() === 'php') { ?>
                                            <?php echo $stars; ?>
                                        <?php } else if (github_card_load_with() === 'js') { ?>
                                            <?php echo github_card_data_preloader() ? $data_loading_icon : $counts_empty_placeholder; ?>
                                        <?php } ?>
                                    </span>
                                    <span>Stars</span>
                                </div>
                            </div>
                        <?php endif; ?>


                        <!-- Forks -->
                        <?php if ($show_forks): ?>
                            <div class="github-card-stat ml-10">
                                <i class="far fa-code-branch"></i>
                                <div class="github-card-stat-text">
                                    <span class="count" data-repo-forks>
                                        <?php if (github_card_load_with() === 'php') { ?>
                                            <?php echo $forks; ?>
                                        <?php } else if (github_card_load_with() === 'js') { ?>
                                            <?php echo github_card_data_preloader() ? $data_loading_icon : $counts_empty_placeholder; ?>
                                        <?php } ?>
                                    </span>
                                    <span>Forks</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="github-card-badges">
                        <a href="<?php echo $repolink; ?>" target="_blank" rel="noopener noreferrer">
                            <i class=" fab fa-github -mr-5"></i>
                        </a>
                    </div>
                </div>

                <?php if (github_card_footer_ribbon()) { ?>
                    <div
                        <?php if (github_card_language_ribbon()): ?>
                        <?php if (github_card_load_with() === 'php' && isset($color_gradient) && !empty($color_gradient)) { ?>
                        style="background: <?php echo esc_attr($color_gradient); ?>;"
                        <?php } else if (github_card_load_with() === 'js') { ?>
                        data-active="true"
                        <?php } ?>
                        <?php endif; ?>
                        class="language-ribbon"></div>
                <?php } else { ?>
                    <div class="ribbon-space"></div>
                <?php } ?>
            </div>
        </div>
    </div>

<?php
    return ob_get_clean();
}

add_shortcode('github_card_template', 'fn_github_card_template');
