<?php

Kirby::plugin('hksagentur/webform', [
    'options' => [
        'cache.ratelimiter' => true,
    ],
    'blockModels' => require __DIR__ . '/config/models/block.php',
    'blueprints' => require __DIR__ . '/config/blueprints.php',
    'collections' => require __DIR__ . '/config/collections.php',
    'hooks' => require __DIR__ . '/config/hooks.php',
    'pageMethods' => require __DIR__ . '/config/methods/page.php',
    'routes' => require __DIR__ . '/config/routes.php',
    'snippets' => require __DIR__ . '/config/snippets.php',
    'templates' => require __DIR__ . '/config/templates.php',
    'translations' => require __DIR__ . '/config/translations.php',
    'validators' => require __DIR__ . '/config/validators.php',
]);
