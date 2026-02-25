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
    'page.render:after' => function (string $contentType, array $data, string $html, Page $page): string {
        if ($contentType === 'html') {
            Flash::put('webform.page.previous', $page->url());
        }

        return $html;
    },
];
