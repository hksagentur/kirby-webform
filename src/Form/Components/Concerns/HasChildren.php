<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Webform\Form\Components;
use Webform\Form\Components\Component;
use Webform\Form\Components\Field;
use Webform\Form\Form;

trait HasChildren
{
    protected array|Components|Closure|null $children = null;

    public function hasChildren(): bool
    {
        return $this->getChildren()->count() > 0;
    }

    public function hasFields(): bool
    {
        return $this->getFields()->count() > 0;
    }

    public function getChildren(): Components
    {
        if ($this->children instanceof Components) {
            return $this->children;
        }

        return $this->children = (new Components(
            $this->evaluate($this->children) ?: []
        ))->form($this->getForm());
    }

    /** @return Components<Field> */
    public function getFields(): Components
    {
        return $this->getChildren()->getFields();
    }

    public function find(string $key): ?Component
    {
        return $this->getChildren()->find($key);
    }

    public function findById(string $id): ?Field
    {
        return $this->getChildren()->findById($id);
    }

    public function findByName(string $name): ?Field
    {
        return $this->getChildren()->findByName($name);
    }

    /** @param array<array-key, Component>|Components|Closure|null $children */
    public function children(array|Components|Closure|null $children): static
    {
        $this->children = $children;

        return $this;
    }

    abstract public function getForm(): ?Form;
}
