<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait CanBeAccepted
{
    public function accepted(bool|Closure $condition = true): static
    {
        $this->rule('accepted', $condition);

        return $this;
    }

    public function denied(bool|Closure $condition = true): static
    {
        $this->rule('denied', $condition);

        return $this;
    }
}
