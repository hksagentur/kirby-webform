<?php

namespace Webform\Cms;

use Webform\Form\Config;
use Webform\Form\Manager;

trait HasForm
{
    protected ?Config $formConfig;

    public function formPath(): string
    {
        return $this->content()->form()->value();
    }

    public function formConfig(): Config
    {
        return $this->formConfig ??= Manager::instance()->config($this->formPath());
    }
}
