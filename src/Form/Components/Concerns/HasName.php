<?php

namespace Webform\Form\Components\Concerns;

trait HasName
{
    protected ?string $name = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
