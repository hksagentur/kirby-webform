<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;

trait DispatchesEvents
{
    public function applyFilters(string $name, array $arguments, string $modify): mixed
    {
        return App::instance()->apply("webform.{$name}", $arguments + [
            'form' => $this,
        ], $modify);
    }

    public function fireEvent(string $name, array $arguments = []): void
    {
        App::instance()->trigger("webform.{$name}", $arguments + [
            'form' => $this,
        ]);
    }
}
