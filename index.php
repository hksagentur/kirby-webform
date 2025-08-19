<?php

Kirby::plugin('hksagentur/webform', [
    'options' => [
        'cache.ratelimiter' => true,
        'referrer.blocks' => 'blocks',
    ],
    'blueprints' => require __DIR__ . '/config/blueprints.php',
    'collections' => require __DIR__ . '/config/collections.php',
    'hooks' => require __DIR__ . '/config/hooks.php',
    'routes' => require __DIR__ . '/config/routes.php',
    'snippets' => require __DIR__ . '/config/snippets.php',
    'templates' => require __DIR__ . '/config/templates.php',
    'translations' => require __DIR__ . '/config/translations.php',
    'validators' => require __DIR__ . '/config/validators.php',
    'blockModels' => require __DIR__ . '/config/models/block.php',
]);
