<?php

namespace Webform\Form\Components;

use Closure;

class Button extends Component
{
    use Concerns\CanBeDisabled;
    use Concerns\HasLabel;
    use Concerns\HasName;
    use Concerns\HasValue;

    protected string $snippet = 'webform/button';

    protected string|Closure $type = 'submit';

    public function __construct(string|Closure|null $label = null)
    {
        $this->label($label);
    }

    public static function create(string|Closure|null $label = null): static
    {
        return new static($label);
    }

    public function getType(): string
    {
        return $this->evaluate($this->type);
    }

    public function type(string|Closure $type): static
    {
        $this->type = $type;

        return $this;
    }
}
