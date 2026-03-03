<?php

namespace Webform\Form\Components;

class Cluster extends LayoutComponent
{
    use Concerns\HasGap;

    protected string $snippet = 'webform/cluster';

    public static function create(): static
    {
        return new static();
    }
}
