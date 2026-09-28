<?php

use Webform\Form\Form;
use Webform\Form\FormInput;
use Webform\Form\FormRepository;
use Webform\Form\FormStatus;

if (! function_exists('webform')) {
    /**
     * Load a webform from a given configuration path.
     */
    function webform(string $path): ?Form
    {
        return FormRepository::instance()->getByPath($path);
    }
}

if (! function_exists('webforminput')) {
    /**
     * Get the input of the most recent form submission.
     */
    function webforminput(?string $channel = null): ?FormInput
    {
        return FormInput::fromSession($channel);
    }
}

if (! function_exists('webformstatus')) {
    /**
     * Get the status of the most recent form submission.
     */
    function webformstatus(): ?FormStatus
    {
        return FormStatus::fromSession();
    }
}
