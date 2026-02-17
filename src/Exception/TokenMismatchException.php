<?php

namespace Webform\Exception;

class TokenMismatchException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
            'key' => 'hksagentur.webform.tokenMismatch',
            'fallback' => 'CSRF token mismatch.',
            'httpCode' => 400,
         ];

        parent::__construct($arguments);
    }
}
