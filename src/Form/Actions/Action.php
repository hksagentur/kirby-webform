<?php

namespace Webform\Form\Actions;

use Webform\Form\Concerns as Foundation;
use Webform\Form\FormSubmission;

abstract class Action
{
    use Concerns\BelongsToForm;
    use Concerns\DispatchesEvents;
    use Foundation\EvaluatesClosures;

    public function getEvaluationContext(): array
    {
        return [
            'action' => $this,
            'form' => $this->getForm(),
            'model' => $this->getForm()?->getModel(),
            'block' => $this->getForm()?->getBlock(),
        ];
    }

    abstract public function execute(FormSubmission $submission): void;
}
