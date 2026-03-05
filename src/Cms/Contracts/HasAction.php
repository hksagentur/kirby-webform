<?php

namespace Webform\Cms\Contracts;

use Webform\Form\Actions\Action;

interface HasAction
{
    public function action(): Action;
}
