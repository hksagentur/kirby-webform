<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasHelp
{
    protected string|Closure|null $help = null;

    public function hasHelp(): bool
    {
        return $this->getHelp() !== null;
    }

    public function getHelp(): ?string
    {
        return $this->evaluate($this->help);
    }

    public function help(string|Closure|null $text): static
    {
        $this->help = $text;

        return $this;
    }
}
