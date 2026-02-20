<?php

namespace Webform\Support\Concerns;

use Webform\Form\Collections\Challenges;
use Webform\Form\Collections\Components;
use Webform\Form\Collections\Fields;
use Webform\Form\Components\Component;

trait QueriesChildren
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

    /**
     * @template TField of Field
     *
     * @return Fields<array-key, TField>
     */
    public function getFields(): Fields
    {
        return $this->getIndex()->fields();
    }

    /**
     * @template TChallenge of Component&ProvidesChallenge
     *
     * @return Challenges<array-key, TChallenge>
     */
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
