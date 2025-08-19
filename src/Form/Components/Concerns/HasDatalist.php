<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasDatalist
{
    protected array|Closure|null $datalistOptions = null;

    public function hasDynamicDatalistOptions(): bool
    {
        return $this->datalistOptions instanceof Closure;
    }

    public function getDatalistOptions(): array
    {
        return $this->evaluate($this->datalistOptions) ?: [];
    }

    public function datalist(array|Closure|null $options): static
    {
        $this->datalistOptions = $options;

        return $this;
    }
}
