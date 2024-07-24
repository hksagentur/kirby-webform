<?php

namespace Webform\Cms;

use Webform\Form\Config;

trait HasForm
{
    protected ?Config $formConfig;

    public function isSubmitted(): bool
    {
        return $this->kirby()->request()->is('POST');
    }

    public function formRoot(): string
    {
        return $this->kirby()->root('webforms') ?? $this->kirby()->root('site') . '/forms';
    }

    public function formPath(): string
    {
        return $this->content()->form()->value();
    }

    public function formHandler(): string
    {
        return $this->content()->handler()->value();
    }

    public function formConfig(): Config
    {
        return $this->formConfig ??= new Config($this->formPath(), $this->formRoot());
    }
}
