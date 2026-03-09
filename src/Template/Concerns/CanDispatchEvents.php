<?php

namespace Webform\Template\Concerns;

use Kirby\Cms\App;

trait CanDispatchEvents
{
    abstract public static function getAlias(): string;

    public function apply(string $event, array $arguments, string $modify): mixed
    {
        return App::instance()->apply("webform.{$event}", array_merge($arguments, [
            static::getAlias() => $this,
        ]), $modify);
    }

    public function dispatch(string $event, array $arguments = []): void
    {
        App::instance()->trigger("webform.{$event}", array_merge($arguments, [
            static::getAlias() => $this,
        ]));
    }
}
