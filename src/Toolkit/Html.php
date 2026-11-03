<?php

namespace Webform\Toolkit;

class Html extends \Kirby\Toolkit\Html
{
    /**
     * Prepare a property value for use inside a `<style>` element.
     *
     * HTML entities are not decoded inside `<style>`, so `esc()` would turn
     * `#fff` into an invalid value. Removes only what lets a value break out
     * of its declaration or the element: `<`, `>`, `{`, `}`, `;`, backslashes
     * and comment delimiters.
     */
    public static function css(string $value): string
    {
        return preg_replace('/[<>{};\\\\]|\/\*|\*\//', '', $value);
    }
}
