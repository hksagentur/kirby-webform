<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Form\Form;
use Webform\Http\Exception\ChallengeFailedException;

class VerifyChallenges extends Middleware
{
    public function handle(Request $request, Closure $next, ?Form $form = null): Response|array|false
    {
        if ($form?->getChallenges()?->verifyAll() === false) {
            throw new ChallengeFailedException();
        }

        return $next($request, $form);
    }
}
