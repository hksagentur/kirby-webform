<?php

namespace Webform\Toolkit;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use stdClass;

/**
 * @implements Arrayable<string, mixed>
 * @implements IteratorAggregate<string, mixed>
 */
abstract class Payload implements Arrayable, IteratorAggregate, Jsonable, JsonSerializable
{
    public function exists(string|array $keys): bool
    {
        $placeholder = new stdClass();

        foreach (A::wrap($keys) as $key) {
            if ($this->data($key, $placeholder) === $placeholder) {
                return false;
            }
        }

        return true;
    }

    public function missing(string|array $keys): bool
    {
        return ! $this->exists($keys);
    }

    abstract public function all(?array $keys = null): array;

    public function only(array $keys): array
    {
        $results = [];

        $data = $this->all();
        $placeholder = new stdClass();

        foreach ($keys as $key) {
            $value = A::get($data, $key, $placeholder);

            if ($value !== $placeholder) {
                A::set($results, $key, $value);
            }
        }

        return $results;
    }

    public function except(array $keys): array
    {
        $results = $this->all();

        A::forget($results, $keys);

        return $results;
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toPrettyJson(int $options = 0): string
    {
        return $this->toJson($options | JSON_PRETTY_PRINT);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    public function jsonSerialize(): array
    {
        return $this->all();
    }

    public function __isset(string $name): bool
    {
        return $this->exists($name);
    }

    public function __get(string $name): mixed
    {
        return $this->data($name);
    }

    public function __call(string $name, array $arguments = []): mixed
    {
        return $this->data($name, ...$arguments);
    }

    public function __debugInfo(): array
    {
        return $this->all();
    }

    abstract protected function data(?string $key = null, mixed $default = null): mixed;
}
