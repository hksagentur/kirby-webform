<?php

namespace Webform\Form\Actions;

use Webform\Form\Actions\Concerns\BelongsToForm;
use Webform\Form\Actions\Concerns\DispatchesEvents;
use Webform\Form\ValidatedInput;
use Webform\Support\Concerns\EvaluatesClosures;

abstract class Action
{
    use BelongsToForm;
    use DispatchesEvents;
    use EvaluatesClosures;

    abstract public function execute(ValidatedInput $input): void;
}
