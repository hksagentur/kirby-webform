<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasExtraAttributes
{
    protected array $extraAttributes = [];

    public function getExtraAttributes(): array
    {
        $attributes = [];

        foreach ($this->extraAttributes as $attribute) {
            $attributes = [
                ...$attributes,
                ...$this->evaluate($attribute),
            ];
        }

        return $attributes;
    }

    public function withExtraAttributes(array|Closure $attributes): static
    {
        $this->extraAttributes[] = $attributes;

        return $this;
    }
}
