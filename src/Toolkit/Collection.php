<?php

namespace Webform\Toolkit;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;

/**
 * @template TKey of array-key
 * @template-covariant TValue of (array|object)
 *
 * @implements Arrayable<TKey, TValue>
 * @implements IteratorAggregate<TKey, TValue>
 */
abstract class Collection implements Arrayable, Countable, Jsonable, JsonSerializable, IteratorAggregate
{
    public function isEmpty(): bool
    {
        return empty($this->all());
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function count(): int
    {
        return count($this->all());
    }

    /**
     * @return array<TKey, TValue>
     */
    abstract public function all(): array;

    /**
     * @return TKey[]
     */
    public function keys(): array
    {
        return array_keys($this->all());
    }

    /**
     * @return TValue[]
     */
    public function values(): array
    {
        return array_values($this->all());
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function some(callable $callback): bool
    {
        return A::some($this->all(), $callback);
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function every(callable $callback): bool
    {
        return A::every($this->all(), $callback);
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function each(callable $callback): static
    {
        foreach ($this->all() as $key => $item) {
            if ($callback($item, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @param ?(callable(TValue, TKey, array<TKey, TValue>): bool) $callback
     */
    public function first(?callable $callback = null): mixed
    {
        if (! is_callable($callback)) {
            return A::first($this->all());
        }

        return A::find($this->all(), $callback);
    }

    /**
     * @param ?(callable(TValue, TKey, array<TKey, TValue>): bool) $callback
     */
    public function last(?callable $callback = null): mixed
    {
        if (! is_callable($callback)) {
            return A::last($this->all());
        }

        return A::find(array_reverse($this->all()), $callback);
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function filter(callable $callback): static
    {
        return new static(A::filter($this->all(), $callback));
    }

    /**
     * @param  (callable(TValue, TKey): bool) $callback
     */
    public function reject(callable $callback): static
    {
        return $this->filter(fn (array|object $item, int|string $key) => ! $callback($item, $key));
    }

    /**
     * @param class-string<TValue> $type
     * @return ?TValue
     */
    public function firstInstanceOf(string $type): mixed
    {
        return $this->first(fn (array|object $item) => $item instanceof $type);
    }

    /**
     * @param class-string<TValue> $type
     * @return static<TKey, TValue>
     */
    public function whereInstanceOf(string $type): static
    {
        return $this->filter(fn (array|object $item) => $item instanceof $type);
    }

    /**
     * @return ?TValue
     */
    public function findBy(string $key, mixed $value): mixed
    {
        return $this->first(fn (array|object $item) => $this->getAttribute($item, $key) === $value);
    }

    public function where(string $key, string $operator, mixed $value): static
    {
        return $this->filter($this->operatorForWhere($key, $operator, $value));
    }

    public function whereNull(string $key): static
    {
        return $this->where($key, '===', null);
    }

    public function whereNotNull(string $key): static
    {
        return $this->where($key, '!==', null);
    }

    public function whereIn(string $key, array $values, bool $strict = false): static
    {
        return $this->filter(fn (array|object $item) => in_array($this->getAttribute($item, $key), $values, $strict));
    }

    public function whereNotIn(string $key, array $values, bool $strict = false): static
    {
        return $this->filter(fn (array|object $item) => ! in_array($this->getAttribute($item, $key), $values, $strict));
    }

    public function pluck(string $value, ?string $key = null): array
    {
        $results = [];

        foreach ($this->all() as $item) {
            $itemValue = $this->getAttribute($item, $value);

            if ($key === null) {
                $results[] = $itemValue;
            } else {
                $itemKey = $this->getAttribute($item, $key);

                if ($itemKey !== null) {
                    $results[$itemKey] = $itemValue;
                }
            }
        }

        return $results;
    }

    /**
     * @return static<string, TValue>
     */
    public function keyBy(string $key): static
    {
        $items = [];

        foreach ($this->all() as $item) {
            $resolvedKey = $this->getAttribute($item, $key);

            if (is_object($resolvedKey)) {
                $resolvedKey = (string) $resolvedKey;
            }

            $items[$resolvedKey] = $item;
        }

        return new static($items);
    }

    /**
     * @param (callable($this): mixed) $callback
     */
    public function tap(callable $callback): static
    {
        $callback($this);

        return $this;
    }

    /**
     * @template TPipeReturnType
     *
     * @param (callable($this): TPipeReturnType) $callback
     * @return TPipeReturnType
     */
    public function pipe(callable $callback): mixed
    {
        return $callback($this);
    }

    /**
     * @template TPipeIntoValue
     *
     * @param class-string<TPipeIntoValue> $class
     * @return TPipeIntoValue
     */
    public function pipeInto(string $class): mixed
    {
        return new $class($this->all());
    }

    /**
     * @template TMapValue
     *
     * @param (callable(TValue, TKey): TMapValue) $callback
     * @return array<TKey, TMapValue>
     */
    public function map(callable $callback): array
    {
        $keys = array_keys($this->all());
        $items = array_map($callback, $this->all(), $keys);

        if (empty($keys) && empty($items)) {
            return [];
        }

        return array_combine($keys, $items);
    }

    /**
     * @param TValue[]|Collection<TKey, TValue> $other
     * @return static<TKey, TValue>
     */
    public function merge(array|self $other): static
    {
        return new static([
            ...$this->all(),
            ...$other,
        ]);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->all();
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    protected function getAttribute(array|object $item, string $key): mixed
    {
        if (is_array($item)) {
            return $this->getAttributeFromArray($item, $key);
        }

        if (is_object($item)) {
            return $this->getAttributeFromObject($item, $key);
        }

        return null;
    }

    protected function getAttributeFromArray(array $item, string $key): mixed
    {
        return $item[$key] ?? null;
    }

    protected function getAttributeFromObject(object $item, string $key): mixed
    {
        return $item->{'get' . Str::studly($key)}();
    }

    protected function operatorForWhere(string $key, string $operator, mixed $value): Closure
    {
        return function (array|object $item) use ($key, $operator, $value) {
            $attribute = $this->getAttribute($item, $key);

            return match ($operator) {
                '===' => $attribute === $value,
                '!=' => $attribute != $value,
                '!==' => $attribute !== $value,
                '>' => $attribute > $value,
                '>=' => $attribute >= $value,
                '<' => $attribute < $value,
                '<=' => $attribute <= $value,
                '<=>' => $attribute <=> $value,
                default => $attribute == $value,
            };
        };
    }
}
