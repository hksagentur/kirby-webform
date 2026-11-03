<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Url;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Kirby\Http\Uri;
use Kirby\Toolkit\I18n;
use Webform\Form\Form;
use Webform\Form\FormStatusType;
use Webform\Http\RedirectResponse;
use Webform\Toolkit\Route;

class VerifyCsrfToken extends Middleware
{
    public function __construct(
        protected readonly string $tokenKey = '_webform_token'
    ) {
    }

    public function isReading(Request $request): bool
    {
        return in_array($request->method(), ['GET', 'HEAD', 'OPTIONS']);
    }

    public function getTokenFromRequest(Request $request): ?string
    {
        return $request->get($this->tokenKey) ?? $request->csrf();
    }

    public function tokensMatch(Request $request): bool
    {
        $token = $this->getTokenFromRequest($request);

        if (! $token) {
            return false;
        }

        return App::instance()->csrf($token);
    }

    public function isSameOrigin(Request $request): bool
    {
        $site = $request->header('Sec-Fetch-Site');

        if ($site !== null) {
            return $site === 'same-origin';
        }

        $origin = $request->header('Origin');

        if (! $origin || $origin === 'null') {
            return false;
        }

        return (new Uri($origin))->base() === Uri::current()->base();
    }

    public function handle(Request $request, Closure $next): Response|array|false
    {
        if (! $this->isReading($request) && ! $this->tokensMatch($request)) {
            return $this->tokenExpired($request, Route::get('form'));
        }

        return $next($request);
    }

    protected function tokenExpired(Request $request, Form $form): RedirectResponse
    {
        $response = new RedirectResponse($form->getContext()->page()?->url() ?? Url::home());

        if ($this->isSameOrigin($request)) {
            $response->withInput(channel: $form->getKey());
        }

        return $response->withMessage(
            text: I18n::translate('hksagentur.webform.status.message.expired'),
            type: FormStatusType::Error,
            channel: $form->getKey(),
        );
    }
}
