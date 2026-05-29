<?php

use Kirby\Cms\Page;
use Webform\Toolkit\Flash;

return [
    'page.render:before' => function (string $contentType, array $data, Page $page): array {
        if ($contentType === 'html') {
            Flash::clear();
        }

        return $data;
    },
];
