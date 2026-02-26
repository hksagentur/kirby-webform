<?php

namespace Webform\Form\Concerns;

use Webform\Toolkit\Alert;

trait HasStatus
{
    protected ?Alert $status = null;

    public function hasStatus(): bool
    {
        return $this->getStatus() !== null;
    }

    public function getStatus(): ?Alert
    {
        return $this->status ??= Alert::fromSession($this->getKey());
    }
}
