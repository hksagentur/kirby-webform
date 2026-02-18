<?php

namespace Webform\Exception;

class HoneypotFilledException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
             'key' => 'hksagentur.webform.honeypotFilled',
             'fallback' => 'Honeypot field was filled.',
             'httpCode' => 422,
         ];

        parent::__construct($arguments);
    }
}
