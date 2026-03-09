<?php

namespace Webform\Template;

use Closure;
use Kirby\Cms\App;
use Kirby\Toolkit\Controller;
use Kirby\Toolkit\Str;
use ReflectionClass;
use Stringable;
use Webform\Toolkit\Htmlable;

abstract class ViewComponent implements Htmlable, Stringable
{
    use Concerns\CanBeRendered;
    use Concerns\CanDispatchEvents;
    use Concerns\CanFormatMessages;

    public static function getAlias(): string
    {
        $reflection = new ReflectionClass(static::class);

        if ($alias = $reflection->getConstant('ALIAS')) {
            return $alias;
        }

        return Str::camel($reflection->getShortName());
    }

    public function getEvaluationContext(): array
    {
        return [
            static::getAlias() => $this,
        ];
    }

    public function getSnippetContext(): array
    {
        return [
            static::getAlias() => $this,
        ];
    }

    public function getMessageContext(): array
    {
        return [];
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
                    ...$this->getEvaluationContext(),
                    ...$inject,
                ],
            );
        }

        return $value;
    }
}
