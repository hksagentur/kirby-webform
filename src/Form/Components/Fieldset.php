<?php

namespace Webform\Form\Components;

use Closure;

class Fieldset extends Component
{
    use Concerns\CanBeDisabled;
    use Concerns\HasLabel;

    protected string $snippet = 'webform/fieldset';

    public function __construct(string|Closure|null $label = null)
    {
        $this->label($label);
    }

    public static function create(string|Closure|null $label = null): static
    {
        return new static($label);
    }
}
