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
 * @extends IteratorAggregate<TKey, TValue>
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
     * @param ?(callable(TValue, TKey): bool) $callback
     */
    public function first(?callable $callback = null): ?Component
    {
        if (! is_callable($callback)) {
            return A::first($this->components);
        }

        foreach ($this->components as $key => $component) {
            if ($callback($component, $key)) {
                return $component;
            }
        }

        return null;
    }

    /**
     * @param ?(callable(TValue, TKey): bool) $callback
     */
    public function last(?callable $callback = null): ?Component
    {
        if (! is_callable($callback)) {
            return A::last($this->components);
        }

        foreach (array_reverse($this->components) as $key => $component) {
            if ($callback($component, $key)) {
                return $component;
            }
        }

        return null;
    }

    public function find(string $key): ?Component
    {
        return $this->findBy('key', $key);
    }

    public function findBy(string $key, mixed $value): ?Component
    {
        return $this->first(fn (Component $component) => $this->getAttribute($component, $key) === $value);
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
        return $this->filter(fn (Component $component) => in_array($this->getAttribute($component, $key), $values, $strict));
    }

    public function whereNotIn(string $key, array $values, bool $strict = false): static
    {
        return $this->filter(fn (Component $component) => ! in_array($this->getAttribute($component, $key), $values, $strict));
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function some(callable $callback): bool
    {
        return $this->first($callback) !== null;
    }

    /**
     * @param (callable(TValue, TKey): bool) $callback
     */
    public function every(callable $callback): bool
    {
        foreach ($this->components as $key => $component) {
            if (! $callback($component, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @template TComponent of TValue
     *
     * @param class-string<TComponent> $type
     * @return ?TComponent
     */
    public function firstInstanceOf(string $type): ?Component
    {
        return $this->first(fn (Component $component) => $component instanceof $type);
    }

    /**
     * @template TComponent of TValue
     *
     * @param class-string<TComponent> $type
     * @return static<TKey, TComponent>
     */
    public function whereInstanceOf(string $type): static
    {
        return $this->filter(fn (Component $component) => $component instanceof $type);
    }

    /**
     * @return string[]
     */
    public function componentKeys(): array
    {
        return array_filter($this->pluck('key'));
    }

    /**
     * @return array<TKey, mixed>
     */
    public function pluck(string $key): array
    {
        return $this->map(fn (Component $component) => $this->getAttribute($component, $key));
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
     * @return static<string, TValue>
     */
    public function keyBy(string $key): static
    {
        $components = [];

        foreach ($this->components as $component) {
            $value = $this->getAttribute($component, $key);

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
     * @template-covariant TField of Field
     *
     * @return Fields<TKey, TField>
     */
    public function fields(): Fields
    {
        return new Fields($this->whereInstanceOf(Field::class)->all());
    }

    /**
     * @template-covariant TChallenge of TValue&ProvidesChallenge
     *
     * @return Challenges<TKey, TChallenge>
     */
    public function challenges(): Challenges
    {
        return new Challenges($this->whereInstanceOf(ProvidesChallenge::class)->all());
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

    /**
     * @param TValue $component
     */
    protected function getAttribute(Component $component, string $key): mixed
    {
        return match ($key) {
            'key' => $component->getKey(),
            'id' => $component->getId(),
            default => null,
        };
    }

    protected function operatorForWhere(string $key, string $operator, mixed $value): Closure
    {
        return function (Component $component) use ($key, $operator, $value) {
            $attribute = $this->getAttribute($component, $key);

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
