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
        'label' => 'Block',
        'label_plural' => 'Blocks',
        'general' => [
            'choose' => 'Choose an image',
        ],
        'featured_image' => [
            'name' => 'Related image',
            'description' => 'Image linked to the blog post',
        ],
    ],

    'settings' => [
        'allowed_blocks' => 'Allowed blocks',
        'allowed_tags' => 'Allowed tags',
        'control' => 'Control',
        'control_description' => 'List of blocks available in the blog editing interface.',
        'control_field' => 'Blocks field definition for blog',
        'control_field_description' => 'This is the generated definition for the blocks field that will be available in the blog editing interface for a post.',
        'ignored_blocks' => 'Ignored blocks',
        'ignored_tags' => 'Ignored tags',
        'name' => 'Block\'s name',
        'not_allowed_block' => 'Unauthorized block',
        'not_allowed_tags' => 'Unauthorized tags',
        'replace_editor' => 'Replace post content editor by blocks',
        'status' => 'Status',
        'tags' => 'Block\'s tags',
        'view_field_defintion' => 'View field definition',
        'tabs' => [
            'blocks' => 'Blocks',
        ],
    ]
];
