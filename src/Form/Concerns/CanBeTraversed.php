<?php

namespace Webform\Form\Concerns;

use Webform\Form\Collections\Challenges;
use Webform\Form\Collections\Components;
use Webform\Form\Collections\Fields;
use Webform\Form\Components\Component;

trait CanBeTraversed
{
    abstract public function getChildren(): Components;

    public function hasChildren(): bool
    {
        return $this->getChildren()->count() > 0;
    }

    public function hasFields(): bool
    {
        return $this->getFields()->count() > 0;
    }

    public function hasChallenges(): bool
    {
        return $this->getChallenges()->count() > 0;
    }

    public function getIndex(): Components
    {
        return $this->getChildren()->index();
    }

    public function getFields(): Fields
    {
        return $this->getIndex()->fields();
    }

    public function getChallenges(): Challenges
    {
        return $this->getIndex()->challenges();
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
