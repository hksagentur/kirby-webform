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
        $challenges = $form?->getChildren()->getIndex()->getChallenges() ?? [];

        foreach ($challenges as $challenge) {
            if (! $challenge->verify()) {
                throw new ChallengeFailedException([
                    'component' => $challenge,
                ]);
            }
        }

        return $next($request, $form);
    }
}
