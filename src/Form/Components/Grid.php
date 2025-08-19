<?php

namespace Webform\Form\Components;

class Grid extends Component
{
    use Concerns\HasGap;

    protected string $snippet = 'webform/grid';

    public static function create(): static
    {
        return new static();
    }
}
