<?php

namespace Webform\Cms\Contracts;

use Webform\Form\Form;

interface HasForm
{
    public function form(): Form;
}
