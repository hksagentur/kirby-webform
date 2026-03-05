<?php

namespace Webform\Form\Actions;

use Closure;
use Webform\Form\FormSubmission;

class Callback extends Action
{
    public function __construct(
        protected Closure $callback
    ) {
    }

    public static function create(Closure $callback): static
    {
        return new static($callback);
    }

    public static function handle(FormSubmission $submission, mixed ...$arguments): mixed
    {
        return static::create(...$arguments)->execute($submission);
    }

    public function getCallback(): Closure
    {
        return $this->callback;
    }

    public function callback(Closure $callback): static
    {
        $this->callback = $callback;

        return $this;
    }

    public function execute(FormSubmission $submission): mixed
    {
        $this->dispatch('callback:before');

        $result = $this->evaluate($this->getCallback(), [
            'submission' => $submission,
        ]);

        $this->dispatch('callback:after', [
            'result' => $result,
        ]);

        return $result;
    }
}
