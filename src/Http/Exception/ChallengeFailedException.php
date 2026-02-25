<?php

namespace Webform\Http\Exception;

use Kirby\Exception\Exception;

class ChallengeFailedException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
             'key' => 'hksagentur.webform.challengeFailed',
             'fallback' => 'Challenge failed.',
             'httpCode' => 422,
         ];

        parent::__construct($arguments);
    }
}
