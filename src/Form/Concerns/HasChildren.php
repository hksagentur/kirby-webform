<?php

namespace Webform\Form\Concerns;

use Closure;
use Webform\Form\Collections\Components;
use Webform\Form\Components\Component;
use Webform\Support\Concerns\QueriesChildren;

trait HasChildren
{
    use QueriesChildren;

    protected array|Components|Closure|null $children = null;

    public function getChildren(): Components
    {
        if ($this->children instanceof Components) {
            return $this->children;
        }

        return $this->children = (new Components(
            $this->evaluate($this->children) ?: []
        ))->form($this);
    }

    /** @param array<array-key, Component>|Components|Closure|null $children */
    public function children(array|Components|Closure|null $children): static
    {
        $this->children = $children;

        return $this;
    }
}
