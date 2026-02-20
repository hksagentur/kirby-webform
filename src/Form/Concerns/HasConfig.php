<?php

namespace Webform\Form\Concerns;

use Webform\Form\FormConfig;

trait HasConfig
{
    protected FormConfig $config;

    public function getConfig(): FormConfig
    {
        return $this->config;
    }

    public function config(FormConfig $config): static
    {
        $this->config = $config;

        return $this;
    }
}
