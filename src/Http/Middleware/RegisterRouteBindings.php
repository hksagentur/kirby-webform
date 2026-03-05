<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Kirby\Toolkit\Str;
use Webform\Form\Form;
use Webform\Form\FormRepository;
use Webform\Toolkit\Route;

class RegisterRouteBindings extends Middleware
{
    public function handle(Request $request, Closure $next): Response|array|false
    {
        Route::bind('form', function () use ($request): ?Form {
            return FormRepository::instance()->getByPath(
                Str::after($request->path(), 'webforms/')
            );
        });

        return $next($request);
    }
}
