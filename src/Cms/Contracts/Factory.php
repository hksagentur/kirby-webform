<?php

namespace Webform\Cms\Contracts;

use Webform\Form\Actions\Action;
use Webform\Form\Form;

interface Factory
{
    /**
     * Get the form.
     */
    public function form(): Form;

    /**
     * Get the action to perform when the form is submitted.
     */
    public function action(): Action;
}
