<?php

use Webform\Form\Form;
use Webform\Form\FormFactory;

if (! function_exists('webform')) {
    /**
     * Load a webform from a given configuration path.
     */
    function webform(string $path): ?Form
    {
        return FormFactory::instance()->createFromConfig($path);
    }
}
