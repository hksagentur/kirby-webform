<?php

namespace Webform\Form\Components\Concerns;

use BackedEnum;
use Closure;
use UnitEnum;
use Kirby\Toolkit\Collection;

trait HasOptions
{
    protected string|array|Closure|Collection|null $options = null;

    public function hasDynamicOptions(): bool
    {
        return $this->options instanceof Closure;
    }

    public function getOptions(): array
    {
        $options = $this->evaluate($this->options) ?: [];

        if (is_string($options) && enum_exists($options)) {
            return $this->optionsFromEnum($options);
        }

        if ($options instanceof Collection) {
            return $this->optionsFromCollection($options);
        }

        return $options;
    }

    public function options(string|array|Closure|Collection|null $options): static
    {
        $this->options = $options;

        return $this;
    }

    protected function optionsFromEnum(string $enum): array
    {
        return array_reduce(
            array: $enum::cases(),
            callback: function (array $options, UnitEnum|BackedEnum $case): array {
                $name = $case->name;
                $value = $case?->value;

                $options[$value ?? $name] = $name;

                return $options;
            },
            initial: [],
        );
    }

    protected function optionsFromCollection(Collection $collection): array
    {
        return array_reduce(
            array: $collection->data(),
            callback: function (array $options, mixed $item) use ($collection): array {
                $slug = $collection->getAttribute($item, 'slug');
                $title = $collection->getAttribute($item, 'title');

                $options[$slug ?? $title] = $title;

                return $options;
            },
            initial: [],
        );
    }
}
