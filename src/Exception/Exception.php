<?php

namespace Webform\Exception;

abstract class Exception extends \Kirby\Exception\Exception
{
    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        parent::__construct($arguments);
    }
}
