<?php

use Kirby\Cms\App;
use Kirby\Cms\Structure;
use Uniform\Form;

if (! function_exists('form')) {
    /**
     * Load a form from configuration.
     *
     * @param string $id The unique ID of the form to load.
     * @return string
     */
    function form(string $id): Form
    {
        $kirby = App::instance();
        $component = $kirby->component('form');

        return $component($kirby, $id);
    }
}
