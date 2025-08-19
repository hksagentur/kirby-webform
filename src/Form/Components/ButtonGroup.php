<?php

namespace Webform\Form\Components;

class ButtonGroup extends Component
{
    protected string $snippet = 'webform/button-group';

    public static function create(): static
    {
        return new static();
    }
}
