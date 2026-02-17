<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;

abstract class Middleware
{
    /** @param (Closure(Request $request): Response|array|false) $next */
    abstract public function handle(Request $request, Closure $next): Response|array|false;
}
