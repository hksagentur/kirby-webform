<?php

namespace Webform\Form\Concerns;

use Closure;
use Webform\Form\Actions\Action;

trait HasActions
{
    /** @var array<Action|Closure> */
    protected array $actions = [];

    /** @return Action[] */
    public function getActions(): array
    {
        $actions = [];

        foreach ($this->actions as $action) {
            $action = $this->evaluate($action);

            if (! is_a($action, Action::class)) {
                continue;
            }

            $actions[] = $action->form($this);
        }

        return $actions;
    }

    /** @param array<Action|Closure> $actions */
    public function actions(array $actions): static
    {
        $this->actions = [
            ...$this->actions,
            ...$actions,
        ];

        return $this;
    }

    public function action(Action|Closure $action): static
    {
        $this->actions[] = $action;

        return $this;
    }
}
