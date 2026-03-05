<?php

namespace Webform\Form\Components;

class ButtonGroup extends LayoutComponent
{
    protected string $snippet = 'webform/button-group';

    public function __construct(array $buttons)
    {
        $this->children($buttons);
    }

    public static function create(array $buttons): static
    {
        return new static($buttons);
    }
}
