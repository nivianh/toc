<?php

return [
    'supported'               => [
        'Botble\Blog\Models\Post',
    ],
    'fragment_prefix'         => 'i',
    'position'                => 'before-first-heading',
    'start'                   => 4,
    'show_heirarchy'          => true,
    'ordered_list'            => false,
    'lowercase'               => true,
    'hyphenate'               => false,
    'bullet_spacing'          => false,
    'heading_levels'          => ['2', '3', '4', '5', '6'],
    'css_container_class'     => '',
    'show_option_in_form'     => true,
    'default_option_in_form'  => 'yes',
    'context_sidebar_in_form' => 'side', // 'top' || 'side'
];
