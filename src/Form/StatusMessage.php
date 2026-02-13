<?php

namespace Webform\Form;

use JsonSerializable;
use Stringable;
use Kirby\Toolkit\Html;
use Throwable;

readonly class StatusMessage implements Stringable, JsonSerializable
{
    public function __construct(
        protected string $message,
        protected ?string $type = 'success',
        protected ?string $role = 'status',
    ) {}

    public static function from(string|array|self $value): ?static
    {
        if ($value instanceof static) {
            return $value;
        }

        if (is_array($value)) {
            return static::fromArray($value);
        }

        return static::fromString( (string) $value);
    }

    public static function tryFrom(string|null|array|self $value): ?static
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

    public static function fromString(string $message, ?string $type = 'success', ?string $role = 'status'): ?static
    {
        return new static($message, $type, $role);
    }

    public static function fromArray(array $attributes): static
    {
        return new static(
            $attributes['message'],
            $attributes['type'] ?? 'success',
            $attributes['role'] ?? 'status',
        );
    }

    public function isSuccess(): bool
    {
        return $this->type === 'success';
    }

    public function isWarning(): bool
    {
        return $this->type === 'warning';
    }

    public function isError(): bool
    {
        return $this->type === 'error';
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function toString(): string
    {
        return $this->getMessage();
    }

    public function toHtml(): string
    {
        return Html::tag('div', $this->getMessage(), [
            'class' => [
                'message',
                'message--'.$this->getType(),
            ],
            'role' => $this->getRole(),
        ]);
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'type' => $this->getType(),
            'role' => $this->getRole(),
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
