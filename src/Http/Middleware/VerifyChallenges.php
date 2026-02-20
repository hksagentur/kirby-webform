<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Exception\ChallengeFailedException;
use Webform\Form\Form;

class VerifyChallenges extends Middleware
{
    public function handle(Request $request, Closure $next, ?Form $form = null): Response|array|false
    {
        $challenges = $form?->getChallenges();

        if ($challenges?->verifyAll() === false) {
            throw new ChallengeFailedException([
                'challenge' => $challenges?->invalid()->first(),
            ]);
        }

        return $next($request, $form);
    }
}
