<?php

namespace Webform\Cms;

use Kirby\Cms\Field;
use Webform\Form\FormConfig;

trait HasForm
{
    protected ?FormConfig $formConfig;

    public function isSubmitted(): bool
    {
        return $this->kirby()->request()->is('POST');
    }

    public function config(): FormConfig
    {
        return $this->formConfig ??= new FormConfig(
            path: $this->content()->form()->value(),
            root: $this->kirby()->root('webforms') ?? $this->kirby()->root('site') . '/forms',
        );
    }

    public function subject(): Field
    {
        return $this->content()->subject()->or($this->config()->emailSubject());
    }

    public function sender(): Field
    {
        return $this->content()->sender()->or($this->config()->emailSender());
    }

    public function recipient(): Field
    {
        return $this->content()->recipient()->or($this->config()->emailRecipient());
    }
}
