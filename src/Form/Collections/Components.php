<?php

namespace Webform\Form\Collections;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use Kirby\Toolkit\A;
use Stringable;
use Webform\Form\Components\Component;
use Webform\Form\Components\Contracts\ProvidesChallenge;
use Webform\Form\Components\Field;
use Webform\Form\Form;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Component
 *
 * @implements IteratorAggregate<TKey, TValue>
 */
class Components implements Countable, IteratorAggregate, Stringable
{
    /**
     * @var ?static<TKey, TValue>
     */
    protected ?self $index = null;

    /**
     * @param array<TKey, TValue>  $components
     */
    public function __construct(
        protected array $components = []
    ) {
    }

    public function isEmpty(): bool
    {
        return empty($this->components);
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function count(): int
    {
        return count($this->components);
    }

    /**
     * @return string[]
     */
    public function keys(): array
    {
        return array_keys($this->components);
    }

    /**
     * @return string[]
     */
    public function componentKeys(): array
    {
        return array_filter($this->pluck('key'));
    }

    /**
     * @param ?(callable(TValue, TKey, array<TKey, TValue>): bool) $callback
     */
    public function first(?callable $callback = null): ?Component
    {
        if (! is_callable($callback)) {
            return A::first($this->components);
        }

        return A::find($this->components, $callback);
    }

    /**
     * @param ?(callable(TValue, TKey, array<TKey, TValue>): bool) $callback
     */
    public function last(?callable $callback = null): ?Component
    {
        if (! is_callable($callback)) {
            return A::last($this->components);
        }

        return A::find(array_reverse($this->components), $callback);
    }

    /**
     * @return ?TValue
     */
    public function find(string $key): ?Component
    {
        return $this->findBy('key', $key);
    }

    /**
     * @return ?TValue
     */
    public function findBy(string $key, mixed $value): ?Component
    {
        return $this->first(fn (Component $component) => $component->getPropertyValue($key) === $value);
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function filter(callable $callback): static
    {
        return new static(A::filter($this->components, $callback));
    }

    /**
     * @param  (callable(TValue, TKey): bool) $callback
     */
    public function reject(callable $callback): static
    {
        return $this->filter(fn (Component $component, int|string $key) => ! $callback($component, $key));
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
        return $this->filter(fn (Component $component) => in_array($component->getPropertyValue($key), $values, $strict));
    }

    public function whereNotIn(string $key, array $values, bool $strict = false): static
    {
        return $this->filter(fn (Component $component) => ! in_array($component->getPropertyValue($key), $values, $strict));
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function some(callable $callback): bool
    {
        return A::some($this->components, $callback);
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function every(callable $callback): bool
    {
        return A::every($this->components, $callback);
    }

    /**
     * @param class-string<TValue> $type
     * @return ?TValue
     */
    public function firstInstanceOf(string $type): ?Component
    {
        return $this->first(fn (Component $component) => $component instanceof $type);
    }

    /**
     * @param class-string<TValue> $type
     * @return static<TKey, TValue>
     */
    public function whereInstanceOf(string $type): static
    {
        return $this->filter(fn (Component $component) => $component instanceof $type);
    }

    /**
     * @template TMapValue
     *
     * @param (callable(TValue, TKey): TMapValue) $callback
     * @return array<TKey, TMapValue>
     */
    public function map(callable $callback): array
    {
        $keys = array_keys($this->components);

        $items = array_map($callback, $this->components, $keys);
        $items = array_combine($keys, $items);

        return $items;
    }

    /**
     * @return array<TKey, mixed>
     */
    public function pluck(string $key): array
    {
        return $this->map(fn (Component $component) => $component->getPropertyValue($key));
    }

    /**
     * @return static<string, TValue>
     */
    public function keyBy(string $key): static
    {
        $components = [];

        foreach ($this->components as $component) {
            $value = $component->getPropertyValue($key);

            if ($value === null) {
                continue;
            }

            $components[$value] = $component;
        }

        return new static($components);
    }

    /**
     * @template TMergeValue of Component
     *
     * @param TMergeValue[]|Components<TKey, TMergeValue> $components
     * @return static<TKey, TValue|TMergeValue>
     */
    public function merge(array|Components $components): static
    {
        return new static([
            ...$this->components,
            ...$components,
        ]);
    }

    /**
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->components;
    }

    /**
     * @return static<TKey, TValue>
     */
    public function index(): static
    {
        if ($this->index !== null) {
            return $this->index;
        }

        $components = [];

        foreach ($this->components as $component) {
            $components = [
                ...$components,
                $component,
                ...$component->getIndex(),
            ];
        }

        return new static($components);
    }

    public function fields(): Fields
    {
        return new Fields(
            $this->whereInstanceOf(Field::class)->all()
        );
    }

    public function challenges(): Challenges
    {
        return new Challenges(
            $this->whereInstanceOf(ProvidesChallenge::class)->all()
        );
    }

    public function form(Form $form): static
    {
        foreach ($this->components as $component) {
            $component->form($form);
        }

        return $this;
    }

    /**
     * @return array<TKey, TValue>
     */
    public function toArray(): array
    {
        return $this->components;
    }

    public function toHtml(): string
    {
        return implode("\n", array_map(fn (Component $component) => $component->toHtml(), $this->components));
    }

    public function toString(): string
    {
        return $this->toHtml();
    }

    /**
     * @return ArrayIterator<TKey, TValue>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->components);
    }

    public function __toString(): string
    {
        return $this->toHtml();
    }

    protected function operatorForWhere(string $key, string $operator, mixed $value): Closure
    {
        return function (Component $component) use ($key, $operator, $value) {
            $property = $component->getPropertyValue($key);

            return match ($operator) {
                '===' => $property === $value,
                '!=' => $property != $value,
                '!==' => $property !== $value,
                '>' => $property > $value,
                '>=' => $property >= $value,
                '<' => $property < $value,
                '<=' => $property <= $value,
                '<=>' => $property <=> $value,
                default => $property == $value,
            };
        };
    }
}
