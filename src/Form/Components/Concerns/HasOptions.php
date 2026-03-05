<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Traversable;
use Webform\Toolkit\Options;

trait HasOptions
{
    protected string|array|Closure|Traversable|null $options = null;

    public function hasDynamicOptions(): bool
    {
        return $this->options instanceof Closure;
    }

    public function getOptions(): Options
    {
        return Options::from(
            $this->evaluate($this->options) ?: []
        );
    }

    public function options(string|array|Closure|Traversable|null $options): static
    {
        $this->options = $options;

        return $this;
    }
}
