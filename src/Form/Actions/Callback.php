<?php

namespace Webform\Form\Actions;

use Closure;
use Kirby\Toolkit\Controller;
use Webform\Form\ValidatedInput;

class Callback extends Action
{
    public function __construct(
        protected Closure $callback
    ) {}

    public static function create(Closure $callback): static
    {
        return new static($callback);
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

    public function execute(ValidatedInput $input): void
    {
        $this->fireEvent('callback:before');

        $form = $this->getForm();
        $callback = $this->getCallback();

        $result = (new Controller($callback))->call(data: [
            ...$form->resolveDefaultEvaluationData(),
            'input' => $input,
        ]);

        $this->fireEvent('callback:after', [
            'result' => $result,
        ]);
    }
}
