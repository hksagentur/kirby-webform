<?php

namespace Webform\Validation;

use Kirby\Toolkit\A;
use Webform\Toolkit\Payload;

class ValidatedInput extends Payload
{
    public function __construct(
        /** @var array<string, mixed> */
        protected array $input = [],
    ) {
    }

    public function isFilled(string|array $keys): bool
    {
        foreach (A::wrap($keys) as $key) {
            if ($this->isEmptyString($key)) {
                return false;
            }
        }

        return true;
    }

    public function isNotFilled(string|array $keys): bool
    {
        foreach (A::wrap($keys) as $key) {
            if (! $this->isEmptyString($key)) {
                return false;
            }
        }

        return true;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->data($key, $default);
    }

    public function all(?array $keys = null): array
    {
        if (is_null($keys)) {
            return $this->input;
        }

        return $this->only($keys);
    }

    protected function isEmptyString(string $key): bool
    {
        $value = $this->data($key);

        if (is_bool($value) || is_array($value) || trim((string) $value) !== '') {
            return false;
        }

        return true;
    }

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return A::get($this->input, $key, $default);
    }
}
