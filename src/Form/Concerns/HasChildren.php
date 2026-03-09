<?php

namespace Webform\Form\Concerns;

use Closure;
use Webform\Form\Collections\Challenges;
use Webform\Form\Collections\Components;
use Webform\Form\Collections\Fields;
use Webform\Form\Components\Component;

trait HasChildren
{
    /** @var Component[]|Components|Closure|null */
    protected array|Components|Closure|null $children = null;

    public function hasChildren(): bool
    {
        return $this->getChildren()->count() > 0;
    }

    public function hasActions(): bool
    {
        return $this->getActions()->count() > 0;
    }

    public function hasChallenges(): bool
    {
        return $this->getChallenges()->count() > 0;
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
        ))->attachTo($this);
    }

    public function getIndex(): Components
    {
        return $this->getChildren()->index();
    }

    public function getActions(): Components
    {
        return $this->getIndex()->actions()->visible();
    }

    public function getChallenges(): Challenges
    {
        return $this->getIndex()->challenges()->visible();
    }

    public function getFields(): Fields
    {
        return $this->getIndex()->fields()->visible();
    }

    /** @param Component[]|Components|Closure|null $children */
    public function children(array|Components|Closure|null $children): static
    {
        $this->children = $children;

        return $this;
    }

    public function find(string $key): ?Component
    {
        return $this->getIndex()->find($key);
    }

    /**
     * @template TComponent of Component
     *
     * @param class-string<TComponent> $type
     * @return ?TComponent
     */
    public function findFirst(string $type): ?Component
    {
        return $this->getIndex()->firstInstanceOf($type);
    }

    /**
     * @template TComponent of Component
     *
     * @param class-string<TComponent> $type
     * @return Components<array-key, TComponent>
     */
    public function findAll(string $type): Components
    {
        return $this->getIndex()->whereInstanceOf($type);
    }
}
