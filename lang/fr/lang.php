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
        'label' => 'Bloc',
        'label_plural' => 'Blocs',
        'general' => [
            'choose' => 'Choisissez une image',
        ],
        'featured_image' => [
            'name' => 'Image de promotion',
            'description' => 'Image liée au post du blog',
        ],
    ],

    'settings' => [
        'allowed_blocks' => 'Blocs autorisés',
        'allowed_tags' => 'Tags autorisés',
        'control' => 'Contrôle',
        'control_description' => 'Liste des blocs qui seront disponibles dans l’interface d’édition du blog.',
        'control_field' => 'Définition du champ des blocs pour le blog',
        'control_field_description' => 'Voici la définition générée pour le champ des blocs qui sera disponible dans l\'interface d\'édition d\un article de blog.',
        'ignored_blocks' => 'Blocs ignorés',
        'ignored_tags' => 'Tags ignorés',
        'name' => 'Nom du bloc',
        'not_allowed_block' => 'Bloc non autorisé',
        'not_allowed_tags' => 'Tags non autorisés',
        'replace_editor' => 'Remplacer l’éditeur de contenu par des blocs',
        'status' => 'Statut',
        'tags' => 'Tags du bloc',
        'view_field_defintion' => 'Voir la définition du champ',
        'tabs' => [
            'blocks' => 'Blocs',
        ],
    ]
];
