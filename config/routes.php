<?php

use Kirby\Cms\App;
use Kirby\Http\Request;
use Webform\Form\Form;
use Webform\Http\Middleware\RateLimited;
use Webform\Http\Middleware\SubstituteBindings;
use Webform\Http\Middleware\VerifyCsrfToken;
use Webform\Http\Middleware\VerifyHoneypot;
use Webform\Http\Middleware\VerifyTimeTrap;
use Webform\Http\Pipeline;
use Webform\Http\SubmissionController;
use Webform\Http\RedirectResponse;

return fn (App $kirby) => [
    [
        'pattern' => 'webforms/(:all)',
        'method' => 'POST',
        'action' => function () use ($kirby): RedirectResponse {
            $middlewares = $kirby->option('hksagentur.webform.middleware', [
                VerifyCsrfToken::class,
                RateLimited::class,
                SubstituteBindings::class,
                VerifyHoneypot::class,
                VerifyTimeTrap::class,
            ]);

            return (new Pipeline($middlewares))->then(function (Request $request, Form $form) use ($kirby) {
                $controller = $kirby->apply('webform.route:before', [
                    'form' => $form,
                    'controller' => new SubmissionController(),
                ], 'controller');

                $response = $controller($request, $form);

                $response = $kirby->apply('webform.route:after', [
                    'form' => $form,
                    'response' => $response,
                ], 'response');

                return $response;
            });
        }
    ],
];
