<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Kirby\Cms\App;

trait CanBeRequired
{
    protected bool|Closure $isRequired = false;

    public function isRequired(): bool
    {
        return $this->evaluate($this->isRequired);
    }

    public function isOptional(): bool
    {
        return ! $this->isRequired();
    }

    public function required(bool|Closure $isRequired = true): static
    {
        $this->isRequired = $isRequired;

        return $this;
    }

    public function hasLabelMarker(): bool
    {
        return $this->getLabelMarker() !== null;
    }

    public function getLabelMarker(): ?string
    {
        return match (App::instance()->option('hksagentur.webform.labelMarker', 'optional')) {
            'optional' => $this->isOptional() ? 'optional' : null,
            'required' => $this->isRequired() ? 'required' : null,
            default => null,
        };
    }
}
