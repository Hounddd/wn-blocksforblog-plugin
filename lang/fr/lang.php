<?php

return [
    'plugin' => [
        'name' => 'BlocksForBlog',
        'description' => 'Fournir des blocs supplémentaires pour le plugin Winter.Blog',
    ],
    'permissions' => [
        'access_settings' => 'Accès à la gestion des paramètres du plugin',
    ],

    'blocks' => [
        'general' => [
            'choose' => 'Choisissez une image',
        ],
        'featured_image' => [
            'name' => 'Image de promotion',
            'description' => 'Image liée au post du blog',
        ],
    ],

    'settings' => [
        'blocks_section' => 'Plugin Blocks',
        'replace_editor' => 'Remplacer l’éditeur de contenu par des blocs',
    ]
];
