<?php

use Webform\Form\Form;
use Webform\Form\FormRepository;

if (! function_exists('webform')) {
    /**
     * Load a webform from a given configuration path.
     */
    function webform(string $path): ?Form
    {
        return FormRepository::instance()->getByPath($path);
    }
}
