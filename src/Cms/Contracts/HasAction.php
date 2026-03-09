<?php

namespace Webform\Cms\Contracts;

use Webform\Action\Action;

interface HasAction
{
    public function action(): Action;
}
