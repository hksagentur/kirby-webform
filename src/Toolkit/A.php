<?php

namespace Webform\Toolkit;

/**
 * @SuppressWarnings(PHPMD.ShortClassName)
 * @SuppressWarnings(PHPMD.CountInLoopExpression)
 */
class A extends \Kirby\Toolkit\A
{
    public static function collapse(array $array): array
    {
        return array_merge([], ...array_values(array_filter($array, is_array(...))));
    }

    public static function forget(array &$array, int|string|array $keys): void
    {
        $original = &$array;

        foreach (static::wrap($keys) as $key) {
            if (isset($array[$key])) {
                unset($array[$key]);
                continue;
            }

            $parts = explode('.', $key);

            $array = &$original;

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    continue 2;
                }
            }

            unset($array[array_shift($parts)]);
        }
    }

    public static function set(array &$array, string $key, mixed $value): array
    {
        if (! str_contains($key, '.')) {
            $array[$key] = $value;
            return $array;
        }

        $parts = explode('.', $key);

        while (count($parts) > 1) {
            $part = array_shift($parts);

            if (! isset($array[$part]) || ! is_array($array[$part])) {
                $array[$part] = [];
            }

            $array = &$array[$part];
        }

        $array[array_shift($parts)] = $value;

        return $array;
    }
}
