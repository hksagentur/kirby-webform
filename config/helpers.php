<?php

use Webform\Form\Form;
use Webform\Form\Manager;

if (! function_exists('webform')) {
    /**
     * Load a webform from a given configuration path.
     *
     * @return ($path is null ? Manager : Form|null)
     */
    function webform(?string $path = null): Form|Manager|null
    {
        if (! is_null($path)) {
            return Manager::instance()->form($path);
        }

        return Manager::instance();
    }
}
