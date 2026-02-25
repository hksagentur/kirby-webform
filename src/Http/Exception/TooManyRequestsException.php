<?php

namespace Webform\Http\Exception;

use Kirby\Exception\Exception;

class TooManyRequestsException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
             'key' => 'hksagentur.webform.tooManyRequests',
             'fallback' => 'Too many requests.',
             'httpCode' => 429,
         ];

        parent::__construct($arguments);
    }
}
