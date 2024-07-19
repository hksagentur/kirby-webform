<?php

namespace Webform\Cms;

use Kirby\Cms\Page;

class FormPage extends Page
{
    use HasForm;

    public function isCacheable(): bool
    {
        return false;
    }
}
