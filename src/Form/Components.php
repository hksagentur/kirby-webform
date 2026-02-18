<?php

namespace Webform\Form;

use ArrayIterator;
use Closure;
use Countable;
use IteratorAggregate;
use Kirby\Toolkit\A;
use Webform\Form\Components\Component;
use Webform\Form\Components\Field;

/**
 * @template TKey of array-key
 * @template-covariant TValue of Component
 * @extends IteratorAggregate<TKey, TValue>
 */
class Components implements Countable, IteratorAggregate
{
    /** @var ?static<Component> */
    protected ?self $index = null;

    /**  @param array<TKey, TValue>  $components */
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

    /** @return static<TKey, Field> */
    public function getFields(): static
    {
        return $this->whereInstanceOf(Field::class);
    }

    /** @return static<TKey, Component> */
    public function getIndex(): static
    {
        if ($this->index !== null) {
            return $this->index;
        }

        $components = [];

        foreach ($this->components as $component) {
            $components = [
                ...$components,
                $component,
                ...$component->getChildren()->getIndex(),
            ];
        }

        return new static($components);
    }

    /** @param ?(callable(TValue, TKey): bool) $callback */
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

    /** @param ?(callable(TValue, TKey): bool) $callback */
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

    /** @param (callable(TValue, TKey): bool) $callback */
    public function filter(callable $callback): static
    {
        return new static(A::filter($this->components, $callback));
    }

    /** @param  (callable(TValue, TKey): bool) $callback */
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
     * @template-covariant TComponent of TValue
     * @param class-string<TComponent> $type
     * @return ?TComponent
     */
    public function firstInstanceOf(string $type): ?Component
    {
        return $this->first(fn (Component $component) => $component instanceof $type);
    }

    /**
     * @template-covariant TComponent of TValue
     * @param class-string<TComponent> $type
     * @return static<TKey, TComponent>
     */
    public function whereInstanceOf(string $type): static
    {
        return $this->filter(fn (Component $component) => $component instanceof $type);
    }

    public function merge(array|Components $components): static
    {
        return new static([
            ...$this->components,
            ...$components,
        ]);
    }

    public function form(Form $form): static
    {
        foreach ($this->components as $component) {
            $component->form($form);
        }

        return $this;
    }

    /** @return array<TKey, TValue> */
    public function all(): array
    {
        return $this->components;
    }

    /** @return array<TKey, TValue> */
    public function toArray(): array
    {
        return $this->components;
    }

    /** @return ArrayIterator<TKey, TValue> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->components);
    }

    protected function getAttribute(Component $component, string $key): mixed
    {
        return match ($key) {
            'key' => $component->getKey(),
            'id' => $component->getId(),
            'name' => $component instanceof Field ? $component->getName() : null,
            'value' => $component instanceof Field ? $component->getValue() : null,
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
