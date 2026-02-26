<?php

namespace Webform\Validation;

use JsonSerializable;
use Stringable;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Jsonable;

/**
 * @implements Arrayable<string, string>
 */
readonly class Message implements Arrayable, Jsonable, JsonSerializable, Stringable
{
    public function __construct(
        protected string $text,
    ) {
    }

    public static function create(string $text): static
    {
        return new static($text);
    }

    public static function from(string|self $value): static
    {
        return match (true) {
            $value instanceof static => $value,
            default => new static((string) $value),
        };
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

    public function jsonSerialize(): string
    {
        return $this->toString();
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
