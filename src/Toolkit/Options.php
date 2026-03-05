<?php

namespace Webform\Toolkit;

use ArrayIterator;
use BackedEnum;
use Closure;
use Countable;
use JsonSerializable;
use InvalidArgumentException;
use IteratorAggregate;
use Kirby\Cms\Page;
use Kirby\Cms\StructureObject;
use Kirby\Cms\User;
use Kirby\Toolkit\A;
use Kirby\Toolkit\Str;
use Stringable;
use Traversable;
use UnitEnum;

/**
 * @template-covariant TValue of array|object
 * @implements IteratorAggregate<int, Option>
 */
class Options implements Countable, Htmlable, IteratorAggregate, Jsonable, JsonSerializable, Stringable
{
    /** @var iterable<TValue> */
    protected iterable $items;

    /** @var ?Closure(Option): bool */
    protected ?Closure $filter = null;

    /** @var ?Closure(Option, Option): int */
    protected ?Closure $sort = null;

    /** @var string|string[]|null */
    protected string|array|null $select = null;

    /** @var (Closure(TValue): string)|string|null */
    protected Closure|string|null $valueResolver = null;

    /** @var (Closure(TValue): string)|string|null */
    protected Closure|string|null $labelResolver = null;

    /** @param iterable<TValue> $items */
    public function __construct(iterable $items)
    {
        $this->items = $items;
    }

    /** @param class-string<BackedEnum|UnitEnum>|iterable $items */
    public static function from(string|iterable $items): static
    {
        if (is_string($items) && enum_exists($items)) {
            $items = $items::cases();
        } elseif ($items instanceof Traversable) {
            $items = iterator_to_array($items, preserve_keys: false);
        }

        if (! is_iterable($items)) {
            throw new InvalidArgumentException(
                'Value must be iterable or an enum class name.'
            );
        }

        return new static($items);
    }

    public function isEmpty(): bool
    {
        return $this->count() === 0;
    }

    public function isNotEmpty(): bool
    {
        return ! $this->isEmpty();
    }

    public function count(): int
    {
        return iterator_count($this->items);
    }

    /** @param (Closure(TValue): string)|string|null $resolver */
    public function valueFrom(Closure|string|null $resolver): static
    {
        $this->valueResolver = $resolver;

        return $this;
    }

    /** @param (Closure(TValue): string)|string|null $resolver */
    public function labelFrom(Closure|string|null $resolver): static
    {
        $this->labelResolver = $resolver;

        return $this;
    }

    /**
     * @param (Closure(TValue): string)|string|null $value
     * @param (Closure(TValue): string)|string|null $label
     */
    public function deriveFrom(Closure|string|null $value, Closure|string|null $label): static
    {
        return $this
            ->valueFrom($value)
            ->labelFrom($label);
    }

    /** @param string|string[]|null $values */
    public function select(string|array|null $values): static
    {
        $this->select = $values;

        return $this;
    }

    /** @param ?Closure(Option): bool $callback */
    public function filter(?Closure $callback): static
    {
        $this->filter = $callback;

        return $this;
    }

    /** @param ?Closure(Option, Option): int $callback */
    public function sort(?Closure $callback = null): static
    {
        $this->sort = $callback ?? static::compareOptionLabel(...);

        return $this;
    }

    public function sortByValue(int $direction = SORT_ASC): static
    {
        return $this->sort(function (Option ...$options) use ($direction) {
            return ($direction === SORT_ASC ? 1 : -1) * static::compareOptionValue(...$options);
        });
    }

    public function sortByLabel(int $direction = SORT_ASC): static
    {
        return $this->sort(function (Option ...$options) use ($direction) {
            return ($direction === SORT_ASC ? 1 : -1) * static::compareOptionLabel(...$options);
        });
    }

    /** @return Option[] */
    public function all(): array
    {
        $options = [];

        foreach ($this->items as $item) {
            if ($this->isRejected($item)) {
                continue;
            }

            $value = $this->resolveValue($item);
            $label = $this->resolveLabel($item);
            $selected = $this->isSelected($value);

            $options[] = new Option(
                $value,
                $label,
                $selected,
            );
        }

        if ($this->sort instanceof Closure) {
            uasort($options, $this->sort);
        }

        return $options;
    }

    /** @return array<array{value: string, label: string, selected: bool}> */
    public function toArray(): array
    {
        return $this->map(fn (Option $option) => $option->toArray());
    }

    /** @return array<array{string,string}> */
    public function toPairs(): array
    {
        return $this->map(fn (Option $option) => $option->toPair());
    }

    /** @return array<array{string,string}> */
    public function toEntries(): array
    {
        return $this->map(fn (Option $option) => $option->toEntry());
    }

    /** @return array<string,string> */
    public function toMap(): array
    {
        return $this->mapWithKeys(fn (Option $option) => [
            $option->value() => $option->label(),
        ]);
    }

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function toHtml(): string
    {
        return implode("\n", $this->map(fn (Option $option) => $option->toHtml()));
    }

    public function toString(): string
    {
        return $this->toHtml();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    /** @return ArrayIterator<int, Option> */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->all());
    }

    /** @return array<array{string,string}> */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /** @param TValue $item */
    protected function isRejected(mixed $item): bool
    {
        if ($this->filter instanceof Closure) {
            return !($this->filter)($item);
        }

        return false;
    }

    protected function isSelected(string $value): bool
    {
        if (is_array($this->select)) {
            return in_array($value, $this->select);
        }

        return $this->select === $value;
    }

    /**
     * @template TMapReturnValue
     *
     * @param Closure(Option): TMapReturnValue $callback
     * @return array<int,TMapReturnValue>
     */
    protected function map(Closure $callback): array
    {
        return array_map($callback, $this->all());
    }

    /**
     * @param Closure(Option): array<string,string> $callback
     * @return array<string,string>
     */
    protected function mapWithKeys(Closure $callback): array
    {
        $options = [];

        foreach ($this as $option) {
            foreach ($callback($option) as $key => $value) {
                $options[$key] = $value;
            }
        }

        return $options;
    }

    /** @param TValue $item */
    protected function resolveValue(mixed $item): string
    {
        if ($this->valueResolver instanceof Closure) {
            return (string)($this->valueResolver)($item);
        }

        if (is_string($this->valueResolver)) {
            return (string)Str::template($this->valueResolver, ['item' => $item]);
        }

        return (string)$this->inferOptionValue($item);
    }

    /** @param TValue $item */
    protected function resolveLabel(mixed $item): string
    {
        if ($this->labelResolver instanceof Closure) {
            return (string) ($this->labelResolver)($item);
        }

        if (is_string($this->labelResolver)) {
            return (string) Str::template($this->labelResolver, ['item' => $item]);
        }

        return (string) $this->inferOptionLabel($item);
    }

    /** @param TValue $item */
    protected function inferOptionValue(mixed $item): mixed
    {
        return match(true) {
            $item instanceof BackedEnum => $item->value,
            $item instanceof UnitEnum => $item->name,
            $item instanceof Page => $item->id(),
            $item instanceof User => $item->id(),
            $item instanceof StructureObject => $item->id(),
            is_array($item) => $item['value'] ?? A::first($item),
            default => (string) $item,
        };
    }

    /** @param TValue $item */
    protected function inferOptionLabel(mixed $item): mixed
    {
        return match(true) {
            $item instanceof BackedEnum => $item->name,
            $item instanceof UnitEnum => $item->name,
            $item instanceof Page =>  $item->label()->or($item->title())->value(),
            $item instanceof User => $item->nameOrEmail(),
            $item instanceof StructureObject => $item->label()->or($item->title())->value(),
            is_array($item) => $item['label'] ?? A::first($item),
            default => (string) $item,
        };
    }

    protected static function compareOptionValue(Option $a, Option $b): int
    {
        return strnatcasecmp($a->value(), $b->value());
    }

    protected static function compareOptionLabel(Option $a, Option $b): int
    {
        return strnatcasecmp($a->label(), $b->label());
    }
}
