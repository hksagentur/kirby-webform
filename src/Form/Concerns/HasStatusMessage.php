<?php

namespace Webform\Form\Concerns;

use Kirby\Toolkit\Str;
use Webform\Form\Manager;
use Webform\Form\StatusMessage;

trait HasStatusMessage
{
    protected ?string $messageBag = null;

    public function getStatusMessage(): ?StatusMessage
    {
        return Manager::instance()->status(
            messageBag: $this->getMessageBag(),
        );
    }

    public function getMessageBag(): ?string
    {
        return $this->messageBag ?? Str::slug($this->getId());
    }

    public function messageBag(string $messageBag): static
    {
        $this->messageBag = $messageBag;

        return $this;
    }
}
