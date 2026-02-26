<?php

namespace Webform\Session;

use Kirby\Cms\App;
use Kirby\Session\SessionData;
use Kirby\Toolkit\A;
use stdClass;

class Flashes
{
    protected static ?self $instance = null;

    protected SessionData $session;
    protected string $sessionKey;

    public function __construct(?SessionData $session = null, string $sessionKey = 'webform.flashes')
    {
        $this->session = $session ?? App::instance()->session()->data();
        $this->sessionKey = $sessionKey;
    }

    public static function instance(?self $instance = null): ?static
    {
        if ($instance !== null) {
            return static::$instance = $instance;
        }

        return static::$instance ?? new static();
    }

    public static function __callStatic(string $method, array $arguments = []): mixed
    {
        return static::instance()->$method(...$arguments);
    }

    public function has(string $key): bool
    {
        $value = $this->get(
            $key,
            $fallback = new stdClass(),
        );

        return $value !== $fallback;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->session->get($key, $default);
    }

    public function put(string $key, mixed $value): static
    {
        $this->session->set($key, $value);

        $this->pushKey('new', $key);
        $this->removeKey('old', $key);

        return $this;
    }

    public function getOrPut(string $key, mixed $value): mixed
    {
        if ($this->has($key)) {
            return $this->get($key);
        }

        $this->put(
            $key,
            $value = is_callable($value) ? $value() : $value,
        );

        return $value;
    }

    public function now(string $key, mixed $value): static
    {
        $this->session->set($key, $value);

        $this->pushKey('old', $key);

        return $this;
    }

    public function keep(string|array $keys): static
    {
        $keys = A::wrap($keys);

        $this->mergeKeys('new', $keys);
        $this->removeKeys('old', $keys);

        return $this;
    }

    public function reflash(): static
    {
        $this->mergeKeys('new', $this->getKeys('old'));
        $this->clearKeys('old');

        return $this;
    }

    public function clear(): static
    {
        $this->session->remove($this->getKeys('old'));

        $this->updateKeys('old', $this->getKeys('new'));
        $this->clearKeys('new');

        return $this;
    }

    protected function getKeys(string $collection): array
    {
        return $this->session->get("{$this->sessionKey}.{$collection}", []);
    }

    protected function updateKeys(string $collection, array $keys): void
    {
        $this->session->set("{$this->sessionKey}.{$collection}", $keys);
    }

    protected function mergeKeys(string $collection, array $keys): void
    {
        $keys = array_merge($this->getKeys($collection), $keys);
        $keys = array_unique($keys);

        $this->updateKeys($collection, $keys);
    }

    protected function removeKeys(string $collection, array $keys): void
    {
        $keys = array_diff($this->getKeys($collection), $keys);

        $this->updateKeys($collection, $keys);
    }

    protected function clearKeys(string $collection): void
    {
        $this->updateKeys($collection, []);
    }

    protected function pushKey(string $collection, string $key): void
    {
        $keys = $this->getKeys($collection);

        $keys[] = $key;

        $this->updateKeys($collection, $keys);
    }

    protected function removeKey(string $collection, string $key): void
    {
        $keys = array_diff($this->getKeys($collection), [$key]);

        $this->updateKeys($collection, $keys);
    }
}
