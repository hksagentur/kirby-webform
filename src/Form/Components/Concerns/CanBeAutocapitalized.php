<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Component;

trait CanBeAutocapitalized
{
    protected bool|string|Closure|null $autocapitalize = null;

    public function getAutocapitalize(): ?string
    {
        return match ($autocapitalize = $this->evaluate($this->autocapitalize)) {
            true => 'on',
            false => 'off',
            default => $autocapitalize,
        };
    }

    public function autocapitalize(bool|string|Closure|null $autocapitalize = true): static
    {
        $this->autocapitalize = $autocapitalize;

        return $this;
    }

    public function disableAutocapitalize(bool|Closure $condition = true): static
    {
        return $this->autocapitalize(static function (Component $component) use ($condition): ?bool {
            return $component->evaluate($condition) ? false : null;
        });
    }
}
