<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Component;

trait CanBeAutocompleted
{
    protected bool|string|Closure|null $autocomplete = null;

    public function getAutocomplete(): ?string
    {
        return match ($autocomplete = $this->evaluate($this->autocomplete)) {
            true => 'on',
            false => 'off',
            default => $autocomplete,
        };
    }

    public function autocomplete(bool|string|Closure|null $autocomplete = true): static
    {
        $this->autocomplete = $autocomplete;

        return $this;
    }

    public function disableAutocomplete(bool|Closure $condition = true): static
    {
        return $this->autocomplete(static function (Component $component) use ($condition): ?bool {
            return $component->evaluate($condition) ? false : null;
        });
    }
}
