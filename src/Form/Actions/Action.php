<?php

namespace Webform\Form\Actions;

use Webform\Form\Actions\Concerns\CanDispatchEvent;
use Webform\Form\Concerns\EvaluatesClosures;
use Webform\Form\FormSubmission;

abstract class Action
{
    use CanDispatchEvent;
    use EvaluatesClosures;

    public function getEvaluationContext(): array
    {
        return [
            'action' => $this,
        ];
    }

    abstract public static function handle(FormSubmission $submission, mixed ...$arguments): mixed;

    abstract public function execute(FormSubmission $submission): mixed;
}
