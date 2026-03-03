<?php

namespace Webform\Form\Components\Contracts;

use Webform\Form\Collections\Components;

interface HasChildren
{
    public function getChildren(): Components;
}
