<?php

namespace Webform\Form;

use Closure;
use Webform\Toolkit\A;
use Webform\Toolkit\Payload;

class FormContext extends Payload
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $data = [],
    ) {
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data($key, $default);
    }

    public function all(?array $keys = null): array
    {
        if (is_null($keys)) {
            return $this->data;
        }

        return $this->only($keys);
    }

    public function add(string|array $key, mixed $value = null): static
    {
        $data = is_array($key) ? $key : [$key => $value];

        foreach ($data as $key => $value) {
            A::set($this->data, $key, $value);
        }

        return $this;
    }

    public function addIf(string $key, mixed $value): static
    {
        if (! $this->exists($key)) {
            $this->add($key, $value);
        }

        return $this;
    }

    public function forget(string|array $keys): static
    {
        A::forget($this->data, $keys);

        return $this;
    }

    public function pull(string $key, mixed $default = null): mixed
    {
        $value = $this->get($key, $default);
        $this->forget($key);

        return $value;
    }

    public function remember(string $key, mixed $value): mixed
    {
        if ($this->exists($key)) {
            return $this->get($key);
        }

        $value = $value instanceof Closure ? $value() : $value;
        $this->add($key, $value);

        return $value;
    }

    public function flush(): static
    {
        $this->data = [];

        return $this;
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->data, $key, $default);
    }
}
