<?php

namespace Webform\Form\Components;

class Select extends Field
{
    use Concerns\CanBeAutocompleted;
    use Concerns\HasOptions;

    protected string $snippet = 'webform/select';
}
