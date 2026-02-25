<?php

namespace Webform\Cms\Contracts;

use Webform\Form\Actions\Action;

interface HasActions
{
    /** @return Action[] */
    public function actions(): array;
}
