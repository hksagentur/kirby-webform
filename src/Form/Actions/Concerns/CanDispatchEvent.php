<?php

namespace Webform\Form\Actions\Concerns;

use Kirby\Cms\App;

trait CanDispatchEvent
{
    public function apply(string $name, array $arguments, string $modify): mixed
    {
        return App::instance()->apply("webform.{$name}", array_merge($arguments, [
            'action' => $this,
        ]), $modify);
    }

    public function dispatch(string $event, array $arguments = []): void
    {
        App::instance()->trigger("webform.{$event}", array_merge($arguments, [
            'action' => $this,
        ]));
    }
}
