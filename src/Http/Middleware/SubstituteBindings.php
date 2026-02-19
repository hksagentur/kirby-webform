<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Kirby\Toolkit\Str;
use Webform\Exception\NotFoundException;
use Webform\Form\Manager;

class SubstituteBindings extends Middleware
{
    public function handle(Request $request, Closure $next): Response|array|false
    {
        $path = Str::after($request->path(), 'webforms/');

        if (! $path) {
            throw new NotFoundException();
        }

        $form = Manager::instance()->form($path);

        if (! $form) {
            throw new NotFoundException();
        }

        if (! $form) {
            throw new NotFoundException();
        }

        return $next($request, $form);
    }
}
