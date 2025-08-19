<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;

trait GeneratesCsrfTokens
{
    public function getCsrfToken(): string
    {
        return App::instance()->csrf();
    }
}
