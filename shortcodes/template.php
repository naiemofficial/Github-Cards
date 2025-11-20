<?php
function fn_github_card_template($atts)
{
    ob_start();
?>

    <div class="github-card-wrapper">
        <div class="github-card">
            <div class="github-card-header">
                <div class="github-card-title">
                    <h3>claitz/<strong>GitHub-Repo-Cards-Generator</strong></h3>
                    <p>GitHub Repo Card Generator is a feature-rich application designated for efforlessly creating SVG cards that showcase Github repositories.</p>
                </div>
                <div class="github-card-user">
                    <div class="github-card-avatar">
                        <img src="https://avatars.githubusercontent.com/u/000000?v=4" alt="User Avatar">
                    </div>
                </div>
            </div>

            <div class="github-card-footer">
                <div class="github-card-footer-wrapper">
                    <div class="github-card-stats">
                        <div class="github-card-stat">
                            <i class="far fa-users"></i>
                            <div class="github-card-stat-text">
                                <span class="count">1</span>
                                <span>Contributors</span>
                            </div>
                        </div>
                        <div class="github-card-stat">
                            <i class="far fa-dot-circle"></i>
                            <div class="github-card-stat-text">
                                <span class="count">0</span>
                                <span>Issues</span>
                            </div>
                        </div>
                        <div class="github-card-stat ml-12">
                            <i class="far fa-star"></i>
                            <div class="github-card-stat-text">
                                <span class="count">7</span>
                                <span>Stars</span>
                            </div>
                        </div>
                        <div class="github-card-stat ml-10">
                            <i class="far fa-code-branch"></i>
                            <div class="github-card-stat-text">
                                <span class="count">2</span>
                                <span>Forks</span>
                            </div>
                        </div>
                    </div>

                    <div class="github-card-badges">
                        <a href="#"><i class="fab fa-github -mr-5"></i></a>
                    </div>
                </div>

                <div class="language-ribbon"></div>
            </div>
        </div>
    </div>

<?php
    return ob_get_clean();
}

add_shortcode('github_card_template', 'fn_github_card_template');
