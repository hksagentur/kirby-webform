<?php

namespace Webform\Validation;

use Countable;
use JsonSerializable;
use Kirby\Toolkit\A;
use Stringable;
use Throwable;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Jsonable;

/**
 * @implements Arrayable<string, string[]>
 */
class Messages implements Arrayable, Countable, Jsonable, JsonSerializable, Stringable
{
    /** @var array<string, string[]> */
    protected array $messages = [];

    public function __construct(array $messages = [])
    {
        foreach ($messages as $key => $value) {
            $this->messages[$key] = array_unique(A::wrap($value));
        }
    }

    public static function from(string|array|self $value): ?static
    {
        if ($value instanceof static) {
            return $value;
        }

        if (is_array($value)) {
            return static::fromArray($value);
        }

        return static::fromString((string) $value);
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

    public static function fromString(string $message): static
    {
        return new static(['error' => $message]);
    }

    public static function fromArray(array $messages = []): static
    {
        return new static($messages);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return $this->count() > 0;
    }

    public function count(): int
    {
        return count($this->messages, COUNT_RECURSIVE) - count($this->messages);
    }

    public function hasAny(string|array|null $keys = null): bool
    {
        if ($this->isEmpty()) {
            return false;
        }

        if (is_null($keys)) {
            return $this->isNotEmpty();
        }

        foreach (A::wrap($keys) as $key) {
            if ($this->first($key) !== null) {
                return true;
            }
        }

        return false;
    }

    public function get(string $key): array
    {
        return $this->messages[$key] ?? [];
    }

    public function first(?string $key = null): ?string
    {
        $messages = is_null($key)
            ? $this->collapse()
            : $this->get($key);

        return A::first($messages);
    }

    public function last(?string $key = null): ?string
    {
        $messages = is_null($key)
            ? $this->collapse()
            : $this->get($key);

        return A::last($messages);
    }

    public function add(string $key, string $message): static
    {
        if ($this->isUnique($key, $message)) {
            $this->messages[$key][] = $message;
        }

        return $this;
    }

    public function all(): array
    {
        return $this->messages;
    }

    public function keys(): array
    {
        return array_keys($this->messages);
    }

    public function unique(): array
    {
        return array_unique($this->collapse());
    }

    public function collapse(): array
    {
        $collection = [];

        foreach ($this->messages as $messages) {
            $collection = [
                ...$collection,
                ...$messages,
            ];
        }

        return $collection;
    }

    public function merge(array|self $messages): static
    {
        $this->messages = array_merge_recursive(
            $this->messages,
            is_array($messages) ? $messages : $messages->all()
        );

        return $this;
    }

    public function toArray(): array
    {
        return $this->all();
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
        return $this->toJson();
    }

    protected function isUnique(string $key, string $message): bool
    {
        return ! isset($this->messages[$key]) || ! in_array($message, $this->messages[$key]);
    }
}
