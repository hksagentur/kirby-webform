<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\App;

trait CanDispatchEvent
{
    public function apply(string $event, array $arguments, string $modify): mixed
    {
        return App::instance()->apply("webform.{$event}", $arguments + [
            'form' => $this,
        ], $modify);
    }

    public function dispatch(string $event, array $arguments = []): void
    {
        App::instance()->trigger("webform.{$event}", $arguments + [
            'form' => $this,
        ]);
    }
}
