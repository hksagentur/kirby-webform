<?php

namespace Webform\Form;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Webform\Support\A;
use Webform\Support\Concerns\InteractsWithData;

readonly class ValidatedInput implements IteratorAggregate, JsonSerializable
{
    use InteractsWithData;

    public function __construct(
        /** @var array<string, mixed> */
        protected array $input = [],
    ) {
    }

    public function input(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->input, $key, $default);
    }

    public function all(?array $keys = null): array
    {
        if (is_null($keys)) {
            return $this->input;
        }

        return $this->only($keys);
    }

    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
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
        return $this->input($name);
    }

    public function __call(string $name, array $arguments = []): mixed
    {
        return $this->input($name, ...$arguments);
    }

    public function __debugInfo(): array
    {
        return $this->all();
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default);
    }
}
