<?php

use Kirby\Cms\App;
use Webform\Cms\FormPage;

return [
    'form' => function (FormPage $page, App $kirby): array {
        return [
            'form' => form($page->formPath()),
        ];
    },
];
