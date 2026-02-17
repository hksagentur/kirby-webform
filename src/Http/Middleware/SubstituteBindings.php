<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Page;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Kirby\Toolkit\Str;
use Webform\Exception\NotFoundException;
use Webform\Form\Form;
use Webform\Form\Manager;

class SubstituteBindings extends Middleware
{
    public function handle(Request $request, Closure $next): Response|array|false
    {
        $path = $request->path();

        $model = match (true) {
            Str::startsWith($path, 'pages/') => $this->resolvePage(Str::after($path, 'pages/')),
            Str::startsWith($path, 'webforms/') => $this->resolveWebform(Str::after($path, 'webforms/')),
            default => null,
        };

        if (! $model) {
            throw new NotFoundException();
        }

        return $next($request, $model);
    }

    protected function resolvePage(string $path): ?Page
    {
        return App::instance()->site()->find($path);
    }

    protected function resolveWebform(string $path): ?Form
    {
        return Manager::instance()->form($path);
    }
}
