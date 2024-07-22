<?php

Kirby::plugin('hksagentur/webform', [
    'options' => [
        'driver' => 'email',
        'guard' => 'honeypot',
    ],
    'blueprints' => [
        'fields/form' => __DIR__ . '/blueprints/fields/form.yml',
        'pages/form' => __DIR__ . '/blueprints/pages/form.yml',
        'sections/form' => __DIR__ . '/blueprints/sections/form.yml',
        '@hksagentur/webform/fields/form' => __DIR__ . '/blueprints/fields/form.yml',
        '@hksagentur/webform/pages/form' => __DIR__ . '/blueprints/pages/form.yml',
        '@hksagentur/webform/sections/form' => __DIR__ . '/blueprints/sections/form.yml',
    ],
    'collections' => [
        'forms' => require __DIR__ . '/collections/forms.php',
    ],
    'commands' => [
        'make:webform' => require __DIR__ . '/commands/make.php',
    ],
    'controllers' => [
        'form' => require __DIR__ . '/controllers/form.php',
    ],
    'pageModels' => [
        'form' => Webform\Cms\FormPage::class,
    ],
    'templates' => [
        'emails/webform/submission.text' => __DIR__ . '/templates/submission.text.php',
        'emails/webform/submission.html' => __DIR__ . '/templates/submission.html.php',
    ],
    'translations' => [
        'en' => require __DIR__ . '/translations/en.php',
        'de' => require __DIR__ . '/translations/de.php',
    ],
]);
