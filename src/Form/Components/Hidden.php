<?php

namespace Webform\Form\Components;

class Hidden extends Component
{
    use Concerns\HasName;
    use Concerns\HasValue;

    protected string $snippet = 'webform/hidden';

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function create(string $name): static
    {
        return new static($name);
    }
}
