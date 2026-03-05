<?php

namespace Webform\Http;

use Closure;
use Webform\Http\Exception\NotFoundException;

class RouteBindings
{
    protected static ?self $instance = null;

    /** @var array<string, Closure> */
    protected array $bindings = [];

    /** @var array<string, object> */
    protected array $resolved = [];

    public static function instance(?self $instance = null): self
    {
        if ($instance !== null) {
            static::$instance = $instance;
        }

        return static::$instance ??= new static();
    }

    public function isBound(string $key): bool
    {
        return isset($this->bindings[$key]);
    }

    public function isResolved(string $key): bool
    {
        return isset($this->resolved[$key]);
    }

    /** @return string[] */
    public function resolved(): array
    {
        return array_keys($this->resolved);
    }

    /** @return string[] */
    public function unresolved(): array
    {
        return array_diff(
            array_keys($this->bindings),
            array_keys($this->resolved),
        );
    }

    public function bind(string $key, object $binding): static
    {
        unset($this->resolved[$key]);

        $this->bindings[$key] = $binding instanceof Closure ? $binding : fn () => $binding;

        return $this;
    }

    public function get(string $key): object
    {
        $object = $this->resolve($key);

        if (! $object) {
            throw new NotFoundException(sprintf(
                'Cannot resolve route binding for "%s"',
                $key
            ));
        }

        return $object;
    }

    public function resolve(string $key, mixed ...$parameters): ?object
    {
        $needsContext = ! empty($parameters);

        if (isset($this->resolved[$key]) && ! $needsContext) {
            return $this->resolved[$key];
        }

        $binding = $this->bindings[$key] ?? null;

        if (! $binding) {
            return null;
        }

        $object = $binding(...$parameters);

        if (! $needsContext) {
            $this->resolved[$key] = $object;
        }

        return $object;
    }

    /** @return object[] */
    public function resolveAll(): array
    {
        foreach ($this->unresolved() as $key) {
            $this->resolve($key);
        }

        return $this->resolved;
    }

    public function flush(): static
    {
        $this->bindings = [];
        $this->resolved = [];

        return $this;
    }

    public function __call(string $name, array $arguments = []): ?object
    {
        return $this->resolve($name, ...$arguments);
    }
}
