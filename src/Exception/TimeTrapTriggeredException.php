<?php

namespace Webform\Exception;

class TimeTrapTriggeredException extends Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $arguments += [
             'key' => 'hksagentur.webform.timeTrapTriggered',
             'fallback' => 'Time trap was triggered.',
             'httpCode' => 422,
         ];

        parent::__construct($arguments);
    }
}
