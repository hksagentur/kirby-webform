<?php

namespace Webform\Cms;

use Kirby\Cms\Field;
use Uniform\Form;

trait HasForm
{
    public function isSubmitted(): bool
    {
        return $this->kirby()->request()->is('POST');
    }

    public function formId(): string
    {
        return $this->content()->template()->value();
    }

    public function formData(?string $key = null): array
    {
        return $this->toForm()->data($key ?? '');
    }

    public function subject(): Field
    {
        return $this->content()->subject()->or($this->emailOption('subject'));
    }

    public function recipient(): Field
    {
        return $this->content()->recipient()->or($this->emailOption('to'));
    }

    public function sender(): Field
    {
        return $this->content()->sender()->or($this->emailOption('from'));
    }

    public function toForm(): Form
    {
        return ($this->kirby()->component('form'))($this->kirby(), $this->formId());
    }

    protected function emailOption(string $key, mixed $default = null): mixed
    {
        return $this->kirby()->option("email.presets.{$this->formId()}.{$key}", $default);
    }
}
