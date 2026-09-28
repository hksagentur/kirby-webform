<?php

namespace Webform\Toolkit\Concerns;

use BackedEnum;
use BadMethodCallException;

trait InvokableCases
{
    public static function __callStatic(string $name, array $arguments)
    {
        $name = strtolower($name);

        foreach (self::cases() as $case) {
            if ($case->name === $name) {
                return $case instanceof BackedEnum ? $case->value : $case->name;
            }
        }

        throw new BadMethodCallException(sprintf(
            'Call to undefined static method %s::%s()',
            static::class,
            $name
        ));
    }
}
