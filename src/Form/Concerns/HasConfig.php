<?php

namespace Webform\Form\Concerns;

use Webform\Form\Config;

trait HasConfig
{
    protected Config $config;

    public function getConfig(): Config
    {
        return $this->config;
    }

    public function config(Config $config): static
    {
        $this->config = $config;

        return $this;
    }

    public function getConfigRoot(): string
    {
        return $this->config->getRoot();
    }

    public function getConfigPath(): string
    {
        return $this->config->getPath();
    }
}
