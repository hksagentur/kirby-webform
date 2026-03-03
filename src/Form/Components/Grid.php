<?php

namespace Webform\Form\Components;

class Grid extends LayoutComponent
{
    use Concerns\HasGap;

    protected string $snippet = 'webform/grid';

    public static function create(): static
    {
        return new static();
    }
}
