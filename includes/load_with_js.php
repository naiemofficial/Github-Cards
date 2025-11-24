<script type="text/javascript">
    // Do AJAX
    document.addEventListener('DOMContentLoaded', function() {
        // Select all GitHub stats placeholders
        console.log(document.querySelectorAll('.github-stats'));
        document.querySelectorAll('.github-stats').forEach(el => {
            const repo = el.dataset.repo;
            if (!repo) return;

            // Fetch GitHub stats via AJAX
            fetch(githubCard.ajaxurl + '?action=fetch_github_stats&repo=' + encodeURIComponent(repo))
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.data) {
                        const stats = data.data;
                        console.log(stats);

                        // Format: Stars, Forks, Issues
                        let formatted = `⭐ ${stats.stars || 0} | 🍴 ${stats.forks || 0} | Issues: ${stats.all_issues || 0}`;

                        // Add languages if available
                        if (stats.languages) {
                            const langs = Object.entries(stats.languages)
                                .map(([lang, pct]) => `${lang} ${pct}%`)
                                .join(', ');
                            formatted += ` | Languages: ${langs}`;
                        }

                        // Replace placeholder content
                        el.textContent = formatted;
                    } else {
                        el.textContent = 'Error loading GitHub stats';
                    }
                })
                .catch(() => {
                    el.textContent = 'Error fetching GitHub stats';
                });
        });
    });
</script>