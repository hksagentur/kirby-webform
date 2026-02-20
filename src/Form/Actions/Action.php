<?php

namespace Webform\Form\Actions;

use Webform\Form\Actions\Concerns\BelongsToForm;
use Webform\Form\Actions\Concerns\DispatchesEvents;
use Webform\Form\FormSubmission;
use Webform\Support\Concerns\EvaluatesClosures;

abstract class Action
{
    use BelongsToForm;
    use DispatchesEvents;
    use EvaluatesClosures;

    abstract public function execute(FormSubmission $submission): void;
}
