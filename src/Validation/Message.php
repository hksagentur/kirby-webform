<?php

namespace Webform\Validation;

use Throwable;

readonly class Message
{
    public function __construct(
        protected string $text,
    ) {
    }

    public static function from(string|self $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        return new static($value);
    }

    public static function tryFrom(string|null|self $value): ?static
    {
        if (empty($value)) {
            return null;
        }

        try {
            return static::from($value);
        } catch (Throwable) {
            return null;
        }
    }

    public function text(): string
    {
        return $this->text;
    }

    public function toString(): string
    {
        return $this->text();
    }

    public function toArray(): array
    {
        return [
            'text' => $this->text,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
