<?php

use Kirby\Cms\App;
use Webform\Cache\RateLimiter;
use Webform\Exception\NotFoundException;
use Webform\Exception\TokenMismatchException;
use Webform\Form\Manager;
use Webform\Http\SubmissionController;
use Webform\Http\RedirectResponse;

return [
    [
        'pattern' => 'webform/(:all)',
        'method' => 'POST',
        'action' => function (string $path): RedirectResponse {
            $kirby = App::instance();
            $manager = Manager::instance();

            $token =  $kirby->request()->get('_webform_token');

            if (! $kirby->csrf($token)) {
                throw new TokenMismatchException();
            }

            $form = $manager->form($path);

            if (! $form) {
                throw new NotFoundException();
            }

            $anonymisedIp = substr(hash('sha256', $kirby->visitor()->ip()), 0, 50);

            $controller = App::instance()->apply('webform.route:before', [
                'form' => $form,
                'controller' => new SubmissionController(),
            ], 'controller');

            $response = RateLimiter::create()->attempt(
                key: $anonymisedIp,
                maxAttempts: 20,
                decayMinutes: 10,
                callback: fn () => $controller($form),
            );

            $response = App::instance()->apply('webform.route:after', [
                'form' => $form,
                'response' => $response,
            ], 'response');

            return $response;
        }
    ],
];
