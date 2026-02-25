<?php

namespace Webform\Toolkit;

use Kirby\Toolkit\Facade;
use Webform\Session\FlashStore;

/**
 * @method static bool has(string $key)
 * @method static mixed get(string $key, mixed $default = null)
 * @method static static put(string $key, mixed $value)
 * @method static mixed getOrPut(string $key, mixed $value)
 * @method static static now(string $key, mixed $value)
 * @method static static keep(string|array $keys)
 * @method static static reflash()
 * @method static static clear()
 *
 * @see FlashStore
 */
class Flash extends Facade
{
    public static function instance(): FlashStore
    {
        return FlashStore::instance();
    }
}
