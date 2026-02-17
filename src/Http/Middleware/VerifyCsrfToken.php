<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Exception\TokenMismatchException;

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

    public function handle(Request $request, Closure $next): Response|array|false
    {
        if (! $this->isReading($request) && ! $this->tokensMatch($request)) {
            throw new TokenMismatchException();
        }

        return $next($request);
    }
}
