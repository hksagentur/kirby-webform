<?php

namespace Webform\Exception;

class NotFoundException extends Exception
{
    protected static string $defaultKey = 'hksagentur.webform.notFound';
    protected static string $defaultFallback = 'Not found';

    protected static int $defaultHttpCode = 404;
}
