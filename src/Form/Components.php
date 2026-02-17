<?php

namespace Webform\Form;

use ArrayIterator;
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
        return $this->whereType(Field::class);
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

    public function findById(string $id): ?Field
    {
        foreach ($this->getFields() as $field) {
            if ($field->getId() === $id) {
                return $field;
            }
        }

        return null;
    }

    public function findByName(string $name): ?Field
    {
        foreach ($this->getFields() as $field) {
            if ($field->getName() === $name) {
                return $field;
            }
        }

        return null;
    }

    public function findByType(string $type): ?Component
    {
        foreach ($this->components as $component) {
            if ($component instanceof $type) {
                return $component;
            }
        }

        return null;
    }

    /**
     * @template-covariant TClass of Component
     * @param class-string<TClass> $type
     * @return static<TClass>
     */
    public function whereType(string $type): static
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
}
