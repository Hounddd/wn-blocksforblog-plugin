<?php

return [
    'plugin' => [
        'name' => 'BlocksForBlog',
        'description' => 'Provide extra blocks for Winter.Blog plugin',
    ],
    'permissions' => [
        'access_settings' => 'Access to plugin settings management',
    ],

    'blocks' => [
        'general' => [
            'choose' => 'Choose an image',
        ],
        'featured_image' => [
            'name' => 'Related image',
            'description' => 'Image linked to the blog post',
        ],
    ],

    'settings' => [
        'blocks_section' => 'Blocks plugin',
        'replace_editor' => 'Replace post content editor by blocks',
    ]
];
