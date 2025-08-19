<?php

namespace Webform\Support\Concerns;

use Closure;

trait Conditionable
{
    public function when(mixed $value, Closure $callback, ?Closure $default = null): mixed
    {
        $value = $value instanceof Closure ? $value($this): $value;

        if ($value) {
            return $callback($this, $value) ?? $this;
        } elseif ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }

    public function unless(mixed $value, Closure $callback, ?Closure $default = null): mixed
    {
        $value = $value instanceof Closure ? $value($this): $value;

        if (! $value) {
            return $callback($this, $value) ?? $this;
        } elseif ($default) {
            return $default($this, $value) ?? $this;
        }

        return $this;
    }
}
