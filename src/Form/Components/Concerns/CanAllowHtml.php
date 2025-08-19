<?php

namespace Webform\Form\Components\Concerns;

use Closure;

trait CanAllowHtml
{
    protected bool|Closure $isHtmlAllowed = false;

    public function isHtmlAllowed(): bool
    {
        return $this->evaluate($this->isHtmlAllowed);
    }

    public function allowHtml(bool|Closure $condition = true): static
    {
        $this->isHtmlAllowed = $condition;

        return $this;
    }
}
