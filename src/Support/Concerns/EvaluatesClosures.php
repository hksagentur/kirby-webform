<?php

namespace Webform\Support\Concerns;

use Closure;
use Kirby\Cms\App;
use Kirby\Toolkit\Controller;

trait EvaluatesClosures
{
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
                    ...$this->resolveDefaultEvaluationData()
                ],
            );
        }

        return $value;
    }

    protected function resolveDefaultEvaluationData(): array
    {
        return [];
    }
}
