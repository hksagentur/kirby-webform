<?php

namespace Webform\Http\Exception;

use Kirby\Exception\Exception;

class NotFoundException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
            'key' => 'hksagentur.webform.notFound',
            'fallback' => 'Not found.',
            'httpCode' => 404,
        ];

        parent::__construct($arguments);
    }
}
