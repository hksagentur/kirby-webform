<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Exception\HoneypotFilledException;
use Webform\Form\Components\Honeypot;
use Webform\Form\Form;

class VerifyHoneypot extends Middleware
{
    public function handle(Request $request, Closure $next, ?Form $form = null): Response|array|false
    {
        $honeypot = $form?->findFirst(Honeypot::class);

        if ($honeypot?->isFilled()) {
            throw new HoneypotFilledException();
        }

        return $next($request, $form);
    }
}
