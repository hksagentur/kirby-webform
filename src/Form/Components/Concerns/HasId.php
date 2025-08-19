<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait HasId
{
    protected string|Closure|null $id = null;

    public function getId(): ?string
    {
        return $this->evaluate($this->id);
    }

    public function id(string|Closure|null $id): static
    {
        $this->id = $id;

        return $this;
    }
}
