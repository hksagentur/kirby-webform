<?php

namespace Webform\Form\Concerns;

use Webform\Toolkit\Alert;

/**
 * @deprecated 3.2.0 Use `webformstatus()` instead.
 */
trait HasStatus
{
    protected ?Alert $status = null;

    /**
     * @deprecated 3.2.0 Use `webformstatus()` instead.
     */
    public function hasStatus(): bool
    {
        return $this->getStatus() !== null;
    }

    /**
     * @deprecated 3.2.0 Use `webformstatus()` instead.
     */
    public function getStatus(): ?Alert
    {
        return $this->status ??= Alert::fromSession($this->getKey());
    }
}
