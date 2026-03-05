<?php

namespace Webform\Toolkit;

use Kirby\Toolkit\Facade;
use Webform\Http\RouteBindings;

/**
 * @method static bool isBound(string $key)
 * @method static bool isResolved(string $key)
 * @method static string[] resolved()
 * @method static string[] unresolved()
 * @method static static bind(string $key, object $binding)
 * @method static object get(string $key)
 * @method static object resolve(string $key, mixed ...$parameters)
 * @method static object[] resolveAll()
 * @method static object flush(string $key)
 *
 * @see RouteBindings
 */
class Route extends Facade
{
    public static function instance(): RouteBindings
    {
        return RouteBindings::instance();
    }
}
