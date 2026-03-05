<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;

trait CanGenerateCsrfTokens
{
    public function generateCsrfToken(): string
    {
        return App::instance()->csrf();
    }
}
