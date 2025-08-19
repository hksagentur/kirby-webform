<?php

namespace Webform\Form\Components;

class Stack extends Component
{
    use Concerns\HasGap;

    protected string $snippet = 'webform/stack';

    public static function create(): static
    {
        return new static();
    }
}
