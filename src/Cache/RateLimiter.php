<?php

namespace Webform\Cache;

use Closure;
use Kirby\Cms\App;
use Kirby\Cache\Cache;
use Webform\Exception\TooManyRequestsException;

class RateLimiter
{
    protected Cache $cache;

    public function __construct(?Cache $cache = null)
    {
        $this->cache = $cache ?? App::instance()->cache('hksagentur.webform.ratelimiter');
    }

    public static function create(?Cache $cache = null): static
    {
        return new static($cache);
    }

    public function attempt(string $key, int $maxAttempts, Closure $callback, int $decayMinutes = 1): mixed
    {
        if ($this->tooManyAttempts($key, $maxAttempts)) {
            throw new TooManyRequestsException();
        }

        $result = $callback();

        $this->increment($key, $decayMinutes);

        return $result;
    }

    public function increment(string $key, int $decayMinutes = 1, int $amount = 1): int
    {
        $expires = $this->cache->getOrSet(
            key: $key.':timer',
            minutes: $decayMinutes,
            result: fn () => (60 * $decayMinutes) + $this->currentTime(),
        );

        $attempts = $this->attempts($key) + $amount;

        $this->cache->set(
            key: $key,
            value: $attempts,
            minutes: round(($expires - $this->currentTime()) / 60),
        );

        return $attempts;
    }

    public function decrement(string $key, int $decayMinutes = 1, int $amount = 1): int
    {
        return $this->increment($key, $decayMinutes, -1 * $amount);
    }

    public function clear(string $key): bool
    {
        $removed = $this->cache->remove($key.':timer');
        $this->cache->remove($key);

        return $removed;
    }

    public function availableIn(string $key): int
    {
        return max(0, $this->cache->get($key.':timer') - $this->currentTime());
    }

    public function attempts(string $key): int
    {
        return $this->cache->get($key, 0);
    }

    public function remaining(string $key, int $maxAttempts): int
    {
        return $maxAttempts - $this->attempts($key);
    }

    public function tooManyAttempts(string $key, int $maxAttempts): int
    {
        return $this->attempts($key) >= $maxAttempts;
    }

    protected function currentTime(): int
    {
        return time();
    }
}
