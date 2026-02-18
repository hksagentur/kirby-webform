<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Exception\TimeTrapTriggeredException;
use Webform\Form\Components\TimeTrap;
use Webform\Form\Form;

class VerifyTimeTrap extends Middleware
{
    public function handle(Request $request, Closure $next, ?Form $form = null): Response|array|false
    {
        $timeTrap = $form?->findFirst(TimeTrap::class);

        if (! $timeTrap) {
            return $next($request, $form);
        }

        $timestamp = $timeTrap->shouldEncrypt()
            ? $timeTrap->getDecryptedValue()
            : $timeTrap->getValue();

        if (! $timestamp) {
            throw new TimeTrapTriggeredException();
        }

        $minDelay = $timeTrap->getMinDelay();

        if ($timestamp > (time() - $minDelay)) {
            throw new TimeTrapTriggeredException();
        }

        return $next($request, $form);
    }
}
