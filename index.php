<?php

Kirby::plugin('hksagentur/webform', [
    'blueprints' => require __DIR__ . '/config/blueprints.php',
    'collections' => require __DIR__ . '/config/collections.php',
    'commands' => require __DIR__ . '/config/commands.php',
    'controllers' => require __DIR__ . '/config/controllers.php',
    'hooks' => require __DIR__ . '/config/hooks.php',
    'routes' => require __DIR__ . '/config/routes.php',
    'snippets' => require __DIR__ . '/config/snippets.php',
    'templates' => require __DIR__ . '/config/templates.php',
    'translations' => require __DIR__ . '/config/translations.php',
    'validators' => require __DIR__ . '/config/validators.php',
    'collectionMethods' => require __DIR__ . '/config/methods/collection.php',
    'blockModels' => require __DIR__ . '/config/models/block.php',
    'pageModels' => require __DIR__ . '/config/models/page.php',
]);
