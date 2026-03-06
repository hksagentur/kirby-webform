<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Webform\Form\Components\Contracts\HasChildren;

trait CanBeHidden
{
    protected bool|Closure $isVisible = true;
    protected bool|Closure $isHidden = false;

    public function isVisible(): bool
    {
        return ! $this->isHidden();
    }

    public function isHidden(): bool
    {
        $hidden = $this->evaluate($this->isHidden);

        if ($hidden) {
            return true;
        }

        $visible = $this->evaluate($this->isVisible);

        if (! $visible) {
            return true;
        }

        if ($this instanceof HasChildren) {
            if ($this->getChildren()->visible()->isEmpty()) {
                return true;
            }
        }

        return false;
    }

    public function visible(bool|Closure $condition = true): static
    {
        $this->isVisible = $condition;

        return $this;
    }

    public function hidden(bool|Closure $condition = true): static
    {
        $this->isHidden = $condition;

        return $this;
    }
}
