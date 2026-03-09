<?php

namespace Webform\Action;

use Closure;
use Kirby\Cms\App;
use Kirby\Toolkit\Controller;
use Webform\Form\FormSubmission;

abstract class Action
{
    abstract public function execute(FormSubmission $submission): mixed;

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

    /** @param array<string, mixed> $inject */
    public function evaluate(mixed $value, array $inject = []): mixed
    {
        if ($value instanceof Closure) {
            return (new Controller($value))->call(
                data: [
                    'kirby' => App::instance(),
                    'site' => App::instance()->site(),
                    'page' => App::instance()->site()->page(),
                    'route' => App::instance()->route(),
                    'request' => App::instance()->request(),
                    ...$inject,
                ],
            );
        }

        return $value;
    }
}
