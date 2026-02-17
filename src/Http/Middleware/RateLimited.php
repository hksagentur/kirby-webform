<?php

namespace Webform\Http\Middleware;

use Closure;
use Kirby\Cms\App;
use Kirby\Http\Request;
use Kirby\Http\Response;
use Webform\Cache\RateLimiter;

class RateLimited extends Middleware
{
    public function __construct(
        protected readonly int $maxAttempts = 20,
        protected readonly int $decayMinutes = 10,
    ) {
    }

    public function getRateLimiter(Request $request): RateLimiter
    {
        return new RateLimiter();
    }

    public function getMaxAttempts(): int
    {
        return $this->maxAttempts;
    }

    public function getDecayMinutes(): int
    {
        return $this->decayMinutes;
    }

    public function getIp(): string
    {
        return App::instance()->visitor()->ip();
    }

    public function getAnonymizedIp(): string
    {
        return substr(hash('sha256', $this->getIp()), 0, 50);
    }

    public function handle(Request $request, Closure $next, mixed ...$args): Response|array|false
    {
        return $this->getRateLimiter($request)->attempt(
            key: $this->getAnonymizedIp(),
            maxAttempts: $this->getMaxAttempts(),
            decayMinutes: $this->getDecayMinutes(),
            callback: fn (): Response|array|false => $next($request, ...$args),
        );
    }
}
