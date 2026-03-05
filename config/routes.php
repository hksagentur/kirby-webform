<?php

use Kirby\Cms\App;
use Kirby\Http\Request;
use Webform\Form\Form;
use Webform\Http\Controller\SubmissionController;
use Webform\Http\Middleware\AddContext;
use Webform\Http\Middleware\RateLimited;
use Webform\Http\Middleware\RegisterRouteBindings;
use Webform\Http\Middleware\VerifyChallenges;
use Webform\Http\Middleware\VerifyCsrfToken;
use Webform\Http\Pipeline;
use Webform\Http\RedirectResponse;

return [
    [
        'pattern' => 'webforms/(:all)',
        'method' => 'POST',
        'action' => function (): RedirectResponse {
            return (new Pipeline(option('hksagentur.webform.middleware', [
                VerifyCsrfToken::class,
                RateLimited::class,
                RegisterRouteBindings::class,
                AddContext::class,
                VerifyChallenges::class,
            ])))->then(function (App $kirby, Request $request, Form $form): RedirectResponse {
                $operation = $request->get('_webform_operation');

                /** @var callable $controller */
                $controller = $kirby->apply('webform.route:before', [
                    'form' => $form,
                    'operation' => $operation,
                    'controller' => new SubmissionController(),
                ], 'controller');

                $response = $controller($form, $operation);

                $response = $kirby->apply('webform.route:after', [
                    'form' => $form,
                    'operation' => $operation,
                    'response' => $response,
                ], 'response');

                return $response;
            });
        }
    ],
];
