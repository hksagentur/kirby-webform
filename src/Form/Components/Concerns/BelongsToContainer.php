<?php

namespace Webform\Form\Components\Concerns;

use Webform\Form\Components\Container;

trait BelongsToContainer
{
    protected ?Container $container = null;

    public function getContainer(): ?Container
    {
        return $this->container;
    }

    public function container(?Container $container): static
    {
        $this->container = $container;

        return $this;
    }
}
