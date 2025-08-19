<?php

namespace Webform\Exception;

use Kirby\Exception\Exception;

class TooManyRequestsException extends Exception
{
    protected static string $defaultKey = 'hksagentur.webform.tooManyRequests';
    protected static string $defaultFallback = 'Too many requests.';

    protected static int $defaultHttpCode = 429;
}
