<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Toolkit\Component;

trait CanBeSpellchecked
{
    protected bool|Closure|null $spellcheck = null;

    public function getSpellcheck(): ?string
    {
        return match ($this->evaluate($this->spellcheck)) {
            true => 'true',
            false => 'false',
            default => null,
        };
    }

    public function spellcheck(bool|Closure|null $spellcheck = true): static
    {
        $this->spellcheck = $spellcheck;

        return $this;
    }

    public function disableSpellcheck(bool|Closure $condition = true): static
    {
        return $this->spellcheck(static function (Component $component) use ($condition): ?bool {
            return $component->evaluate($condition) ? false : null;
        });
    }
}
