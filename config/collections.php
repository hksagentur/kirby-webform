<?php

use Kirby\Cms\App;
use Webform\Form\ConfigCollection;

return [
    'forms' => function (App $kirby): ConfigCollection {
        return ConfigCollection::fromDirectory(
            $kirby->root('webforms') ?? $kirby->root('site') . '/forms'
        );
    },
];
