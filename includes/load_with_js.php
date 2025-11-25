<?php
global $data_loading_icon, $counts_empty_placeholder;
?>
<script type="text/javascript">
    // Do AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Select all GitHub Repo placeholders
        document.querySelectorAll('[data-github-repo]').forEach(repoCard => {
            const repo = repoCard.dataset.githubRepo;
            if (!repo) return;

            const wrapperPreloaderElement = repoCard.querySelector('.github-card-wrapper-preloader');

            const parameters = JSON.parse(repoCard.dataset.parameters || '{}');
            const description_words = parameters['description-words'] || -1;


            const descriptionElement = repoCard.querySelector('p[data-repo-description]');
            const avatarElement = repoCard.querySelector('[data-repo-avatar]');
            const imgElement = avatarElement ? avatarElement.querySelector('img') : null;
            const contributorsCountElement = repoCard.querySelector('[data-repo-contributors]');
            const issuesCountElement = repoCard.querySelector('[data-repo-issues]');
            const starsCountElement = repoCard.querySelector('[data-repo-stars]');
            const forksCountElement = repoCard.querySelector('[data-repo-forks]');

            // Fetch GitHub stats via AJAX
            fetch(`${githubCardRepo.ajax_url}?action=fetch_github_repo_data&repo=${encodeURIComponent(repo)}&nonce=${githubCardRepo.nonce}`)
                .then(res => res.json())
                .then(data => {

                    if (data.success && data.data) {
                        const $repo_data = data.data;
                        const $description = get_or_null($repo_data, 'description');

                        const $user = get_or_null($repo_data, 'user');
                        const $user_avatar_url = get_or_null($user, 'avatar_url');

                        const $contributors = get_or_null($repo_data, 'contributors');
                        const $issues = get_or_null($repo_data, 'all_issues');
                        const $stars = get_or_null($repo_data, 'stars');
                        const $forks = get_or_null($repo_data, 'forks');

                        $color_gradient = get_or_null($repo_data, 'color_gradient');



                        // ------------------- START - Update the repoCard with fetched data ------------------- //

                        // Description
                        if (descriptionElement && $description) {
                            let descText = $description;
                            if (description_words > 0) {
                                const wordsArray = descText.split(' ');
                                if (wordsArray.length > description_words) {
                                    descText = wordsArray.slice(0, description_words).join(' ') + '...';
                                }
                            }
                            descriptionElement.textContent = descText;
                        }

                        // User Avatar
                        if (imgElement && $user_avatar_url) {
                            imgElement.src = $user_avatar_url;
                        }


                        // Contributors
                        if (contributorsCountElement) {
                            contributorsCountElement.textContent = $contributors !== null ? $contributors : '<?php echo esc_js($counts_empty_placeholder); ?>';
                        }

                        // Issues
                        if (issuesCountElement) {
                            issuesCountElement.textContent = $issues !== null ? $issues : '<?php echo esc_js($counts_empty_placeholder); ?>';
                        }

                        // Stars
                        if (starsCountElement) {
                            starsCountElement.textContent = $stars !== null ? $stars : '<?php echo esc_js($counts_empty_placeholder); ?>';
                        }

                        // Forks
                        if (forksCountElement) {
                            forksCountElement.textContent = $forks !== null ? $forks : '<?php echo esc_js($counts_empty_placeholder); ?>';
                        }


                        const languageRibbonElement = repoCard.querySelector('.language-ribbon[data-active="true"]');
                        if (languageRibbonElement && $color_gradient) {
                            languageRibbonElement.style.background = 'linear-gradient(to right, ' + $color_gradient + ')';
                        }
                        // ------------------- END - Update the repoCard with fetched data ------------------- //

                    } else {
                        const error = data.data;
                        const message = error && error.message ? error.message : {
                            message: 'Error fetching repository data.'
                        };
                        const errors = message ? message.errors : message;

                        // Show error to description
                        if (descriptionElement) {
                            descriptionElement.textContent = JSON.stringify(message);
                            descriptionElement.classList.add('error');
                        }

                        // Replace counts with empty placeholder
                        [contributorsCountElement, issuesCountElement, starsCountElement, forksCountElement].forEach(countElement => {
                            if (countElement) {
                                countElement.innerHTML = '<?php echo $counts_empty_placeholder; ?>';
                            }
                        });
                    }
                })
                .catch(() => {
                    // repoCard.textContent = 'Error loading data.';
                    if (descriptionElement) {
                        descriptionElement.textContent = 'Error loading data.';
                        descriptionElement.classList.add('error');
                    }
                })
                .finally(() => {
                    // Remove loading state
                    // Wrapper preloader
                    if (wrapperPreloaderElement) {
                        wrapperPreloaderElement.remove();
                    }

                    // Remove avatar preloader
                    if (avatarElement) {
                        const avatar_preloader = avatarElement.querySelector('.avatar-preloader');
                        if (avatar_preloader) {
                            avatar_preloader.remove();
                        }
                    }
                });
        });
    });
</script>