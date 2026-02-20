<?php

namespace Webform\Form\Components;

class RadioGroup extends Field
{
    use Concerns\CanBeReadOnly;
    use Concerns\HasOptions;

    protected string $snippet = 'webform/radio-group';
}
