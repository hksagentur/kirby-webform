<?php

namespace Webform\Form\Concerns;

use Closure;
use Kirby\Toolkit\A;
use Webform\Form\Components\Component;
use Webform\Form\Components\Field;

trait HasComponents
{
    /** @var array<Component|Closure> */
    protected array $components = [];

    /** @return Component[] */
    public function getComponents(int $depth = PHP_INT_MAX): array
    {
        $components = [];

        foreach ($this->components as $component) {
            $component = $this->evaluate($component);

            if (! is_a($component, Component::class)) {
                continue;
            }

            $components[] = $component->form($this);

            if ($depth <= 1) {
                continue;
            }

            $components = [
                ...$components,
                ...$component->getChildComponents($depth - 1),
            ];
        }

        return $components;
    }

    /** @return Component[] */
    public function getComponentsByType(string $type): array
    {
        return array_filter(
            array: $this->getComponents(),
            callback: static fn (Component $component) => $component instanceof $type,
        );
    }

    public function getComponent(string|Closure $needle): ?Component
    {
        return A::find(
            array: $this->getComponents(),
            callback: $this->componentRetriever($needle),
        );
    }

    /** @return Field[] */
    public function getFields(int $depth = PHP_INT_MAX): array
    {
        return array_filter(
            array: $this->getComponents($depth),
            callback: static fn (Component $component) => $component instanceof Field,
        );
    }

    /** @return Field[] */
    public function getFieldsByType(string $type): array
    {
        return array_filter(
            array: $this->getFields(),
            callback: static fn (Field $field) => $field instanceof $type,
        );
    }

    public function getField(string|Closure $needle): ?Field
    {
        return A::find(
            array: $this->getFields(),
            callback: $this->componentRetriever($needle),
        );
    }

    /** @param array<Component|Closure> $components */
    public function components(array $components): static
    {
        $this->components = [
            ...$this->components,
            ...$components,
        ];

        return $this;
    }

    /** @param Component|Closure $components */
    public function component(Component|Closure $component): static
    {
        $this->components[] = $component;

        return $this;
    }

    protected function componentRetriever(string|Closure $needle): Closure
    {
        if (is_string($needle)) {
            return static function (Component $component) use ($needle): bool {
                $key = $component->getKey();

                if ($key === null) {
                    return false;
                }

                return $key === $needle;
            };
        }

        return $needle;
    }
}
