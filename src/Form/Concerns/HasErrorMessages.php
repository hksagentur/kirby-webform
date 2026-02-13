<?php

namespace Webform\Form\Concerns;

use Kirby\Toolkit\Str;
use Webform\Form\Manager;
use Webform\Form\MessageBag;

trait HasErrorMessages
{
    protected ?string $errorBag = null;

    public function getErrorMessages(): MessageBag
    {
        return Manager::instance()->errors(
            errorBag: $this->getErrorBag(),
        );
    }

    public function getErrorBag(): ?string
    {
        return $this->errorBag ?? Str::slug($this->getId());
    }

    public function errorBag(string $errorBag): static
    {
        $this->errorBag = $errorBag;

        return $this;
    }
}
