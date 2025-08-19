<?php

namespace Webform\Form\Components\Concerns;

use Closure;
use Webform\Form\Components\Component;

trait HasChildComponents
{
    protected array $childComponents = [];

    /** @return Component[] */
    public function getChildComponents(int $depth = PHP_INT_MAX): array
    {
        $components = [];

        foreach ($this->childComponents as $component) {
            $component = $this->evaluate($component);

            if (! is_a($component, Component::class)) {
                continue;
            }

            $components[] = $component->form($this->getForm());

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

    /** @param Component[]|Closure $components */
    public function childComponents(array|Closure $components): static
    {
        $this->childComponents = $components;

        return $this;
    }

    /** @param Component|Closure $components */
    public function childComponent(Component|Closure $component): static
    {
        $this->childComponents[] = $component;

        return $this;
    }

    /** @param Component[]|Closure $components */
    public function children(array|Closure $components): static
    {
        $this->childComponents($components);

        return $this;
    }
}
