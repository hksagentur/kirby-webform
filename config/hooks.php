<?php

use Kirby\Cms\Page;
use Webform\Session\TransientData;

return [
    'page.render:before' => function (string $contentType, array $data, Page $page): array {
        if ($contentType === 'html') {
            TransientData::instance()->cleanUp();
        }

        return $data;
    },
    'page.render:after' => function (string $contentType, array $data, string $html, Page $page): string {
        if ($contentType === 'html') {
            TransientData::instance()->put('webform.page.previous', $page->url());
        }

        return $html;
    },
];
