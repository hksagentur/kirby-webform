<?php

namespace Webform\Form\Concerns;

use Closure;
use Kirby\Cms\App;
use Kirby\Toolkit\Controller;

trait EvaluatesClosures
{
    abstract public function getEvaluationContext(): array;

    public function evaluate(mixed $value): mixed
    {
        if ($value instanceof Closure) {
            return (new Controller($value))->call(
                data: [
                    'kirby' => App::instance(),
                    'site' => App::instance()->site(),
                    'page' => App::instance()->site()->page(),
                    'route' => App::instance()->route(),
                    'request' => App::instance()->request(),
                    ...$this->getEvaluationContext(),
                ],
            );
        }

        return $value;
    }
}
