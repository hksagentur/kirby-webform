<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Form\Form;
use Webform\Http\Exception\ChallengeFailedException;
use Webform\Toolkit\Route;

class VerifyChallenges extends Middleware
{
    public function handle(Request $request, Closure $next): Response|array|false
    {
        /** @var Form $form */
        $form = Route::get('form');

        if ($form->getChallenges()?->verifyAll() === false) {
            throw new ChallengeFailedException();
        }

        return $next($request);
    }
}
