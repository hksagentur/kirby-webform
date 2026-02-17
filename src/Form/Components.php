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
 * @template-covariant TValue of Component
 * @extends IteratorAggregate<int, TValue>
 */
class Components implements Countable, IteratorAggregate
{
    /** @var ?static<Component> */
    protected ?self $index = null;

    /**  @param array<int, TValue>  $components */
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

    /** @return static<Field> */
    public function getFields(): static
    {
        return $this->whereInstanceOf(Field::class);
    }

    /** @return static<Component> */
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

    public function first(): ?Component
    {
        return A::first($this->components);
    }

    public function last(): ?Component
    {
        return A::last($this->components);
    }

    public function find(string $key): ?Component
    {
        foreach ($this->components as $component) {
            if ($component->getKey() === $key) {
                return $component;
            }
        }

        return null;
    }

    public function findBy(string $key, mixed $value): ?Component
    {
        foreach ($this->components as $component) {
            if ($this->getAttribute($component, $key) === $value) {
                return $component;
            }
        }

        return null;
    }

    /** @param  (callable(TValue, int): bool) $callback */
    public function filter(callable $callback): static
    {
        return new static(A::filter($this->components, $callback));
    }

    /** @param  (callable(TValue, int): bool) $callback */
    public function reject(callable $callback): static
    {
        return $this->filter(fn (Component $component, int $index) => ! $callback($component, $index));
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
     * @template-covariant TClass of Component
     * @param class-string<TClass> $type
     * @return static<TClass>
     */
    public function whereInstanceOf(string $type): static
    {
        $components = [];

        foreach ($this->components as $component) {
            if ($component instanceof $type) {
                $components[] = $component;
            }
        }

        return new static($components);
    }

    public function count(): int
    {
        return count($this->components);
    }

    public function form(Form $form): static
    {
        foreach ($this->components as $component) {
            $component->form($form);
        }

        return $this;
    }

    /** @return array<int, TValue> */
    public function all(): array
    {
        return $this->components;
    }

    /** @return array<int, TValue> */
    public function toArray(): array
    {
        return $this->components;
    }

    /** @return ArrayIterator<int, TValue> */
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
