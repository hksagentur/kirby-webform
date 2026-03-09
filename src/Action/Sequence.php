<?php

namespace Webform\Action;

use Closure;
use InvalidArgumentException;
use Webform\Form\FormSubmission;

class Sequence extends Action
{
    public function __construct(
        /** @var (Action|Closure)[] */
        protected array $actions = [],
    ) {
    }

    /** @param (Action|Closure)[] $actions */
    public static function create(array $actions = []): static
    {
        return new static($actions);
    }

    public function add(Closure|Action $action): static
    {
        $this->actions[] = $action;

        return $this;
    }

    public function addIf(mixed $condition, Closure|Action $action): static
    {
        $value = $condition instanceof Closure ? $condition() : $condition;

        if ($value) {
            $this->add($action);
        }

        return $this;
    }

    public function execute(FormSubmission $submission): mixed
    {
        foreach ($this->actions as $action) {
            $this->call($action, $submission);
        }

        return true;
    }

    public function executeUntil(FormSubmission $submission): mixed
    {
        foreach ($this->actions as $action) {
            if ($this->call($action, $submission) === false) {
                return false;
            }
        }

        return true;
    }

    protected function call(Closure|Action $action, mixed ...$arguments): mixed
    {
        return match (true) {
            $action instanceof Action => $action->execute(...$arguments),
            $action instanceof Closure => $action(...$arguments),
            default => throw new InvalidArgumentException('Invalid action'),
        };
    }
}
