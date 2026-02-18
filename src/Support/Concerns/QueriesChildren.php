<?php

namespace Webform\Support\Concerns;

use Webform\Form\Components;
use Webform\Form\Components\Component;

trait QueriesChildren
{
    abstract public function getChildren(): Components;

    public function hasChildren(): bool
    {
        return $this->getChildren()->count() > 0;
    }

    public function hasChild(string $key): bool
    {
        return $this->getChildren()->find($key) !== null;
    }

    public function hasFields(): bool
    {
        return $this->getFields()->count() > 0;
    }

    public function hasField(string $key): bool
    {
        return $this->getFields()->find($key) !== null;
    }

    /** @return Components<Field> */
    public function getFields(): Components
    {
        return $this->getChildren()->getFields();
    }

    public function find(string $key): ?Component
    {
        return $this->getChildren()->getIndex()->find($key);
    }

    /**
     * @template-covariant TComponent of Component
     * @param class-string<TComponent> $type
     * @return ?TComponent
     */
    public function findFirst(string $type): ?Component
    {
        return $this->getChildren()->getIndex()->firstInstanceOf($type);
    }

    /**
     * @template-covariant TComponent of Component
     * @param class-string<TComponent> $type
     * @return Components<array-key, TComponent>
     */
    public function findAll(string $type): Components
    {
        return $this->getChildren()->getIndex()->whereInstanceOf($type);
    }
}
