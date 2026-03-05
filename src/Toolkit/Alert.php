<?php

namespace Webform\Toolkit;

use JsonSerializable;
use Kirby\Cms\App;
use Stringable;

/**
 * @implements Arrayable<string, string>
 */
class Alert implements Arrayable, Htmlable, Jsonable, JsonSerializable, Stringable
{
    public function __construct(
        protected string $message,
        protected string $type = 'success',
    ) {
    }

    public static function create(string $message, string $type = 'success'): static
    {
        return new static($message, $type);
    }

    public static function dispatch(string $message, string $type = 'success', string $channel = 'default'): void
    {
        static::create($message, $type)->flash($channel);
    }

    public static function from(string|self $message): static
    {
        return match (true) {
            $message instanceof static => $message,
            default => new static((string) $message),
        };
    }

    public static function fromSession(string $channel = 'default'): ?static
    {
        $message = Flash::get("webform.form.{$channel}.message");

        return match (true) {
            is_array($message) => new static($message['message'], $message['type'] ?? 'success'),
            is_string($message) => new static($message),
            default => null,
        };
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

    public function type(): string
    {
        return $this->type;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function flash(string $channel = 'default'): void
    {
        Flash::put("webform.form.{$channel}.message", $this->toArray());
    }

    public function toString(): string
    {
        return $this->message;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->message,
            'type' => $this->type,
        ];
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toHtml(): string
    {
        return App::instance()->snippet('webform/message', [
            'message' => $this->toString(),
            'role' => 'status',
            'live' => 'polite',
        ]);
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
