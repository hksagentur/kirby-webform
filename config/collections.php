<?php

use Kirby\Cms\App;
use Kirby\Filesystem\Dir;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Collection;
use Webform\Form\FormConfig;

return [
    'webforms' => function (App $kirby): Collection {
        $files = A::map(
            array: Dir::read($kirby->root('webforms') ?? $kirby->root('site') . '/forms'),
            map: fn (string $file) => new FormConfig($file),
        );

        $files = A::keyBy(
            array: $files,
            keyBy: fn (FormConfig $config) => $config->getPath(),
        );

        return new Collection($files);
    },
];
