<?php

use Webform\Form\Form;
use Webform\Form\Manager;

if (! function_exists('webform')) {
    /**
     * Load a webform from a given configuration path.
     */
    function webform(?string $path = null): Form|Manager
    {
        if (! is_null($path)) {
            return Manager::instance()->form($path);
        }

        return Manager::instance();
    }
}
