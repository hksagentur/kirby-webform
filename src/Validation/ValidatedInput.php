<?php

namespace Webform\Validation;

use Kirby\Toolkit\A;
use Webform\Toolkit\Payload;

readonly class ValidatedInput extends Payload
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

    protected function data(?string $key = null, mixed $default = null): mixed
    {
        return $this->input($key, $default);
    }
}
