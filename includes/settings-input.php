<?php
// Default values for GitHub Card plugin
return [
    'github_card_load_with'        => [
        'type' => 'radio',
        'label' => 'Load With',
        'values' => [
            'php' => 'PHP',
            'js' => 'Javascript',
        ],
        'default' => 'js',
        'groups' => ['card-settings'],
    ],
    'github_card_preloader_type'   => [
        'type' => 'radio',
        'label' => 'Preloader Type',
        'values' => ['spinner', 'skeleton'],
        'default' => 'spinner',
        'groups' => ['card-settings'],
    ],
    'github_card_wrapper_preloader' => [
        'type' => 'checkbox',
        'label' => 'Wrapper Preloader',
        'default' => 'on',
        'groups' => ['card-settings'],
    ],
    'github_card_counts_preloader' => [
        'type' => 'checkbox',
        'label' => 'Repo Counts Preloader',
        'default' => 'on',
        'groups' => ['card-settings'],
    ],
    'github_card_auto_scale'       => [
        'type' => 'checkbox',
        'label' => 'Enable Auto Scale',
        'default' => 'on',
        'groups' => ['card-settings'],
    ],
    'github_card_cache_enabled'    => [
        'type' => 'checkbox',
        'label' => 'Cache',
        'default' => 'on',
        'groups' => ['cache-settings'],
    ],
    'github_card_cache_duration'   => [
        'type' => 'number',
        'label' => 'Cache Duration (seconds)',
        'default' => 3600,
        'groups' => ['cache-settings'],
    ],
];
