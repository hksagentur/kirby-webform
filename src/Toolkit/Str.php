<?php

namespace Webform\Toolkit;

class Str extends \Kirby\Toolkit\Str
{
    public static function classBasename(string|object $class): string
    {
        return basename(str_replace('\\', '/', is_object($class) ? get_class($class) : $class));
    }
}
