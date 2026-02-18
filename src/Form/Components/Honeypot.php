<?php

namespace Webform\Form\Components;

class Honeypot extends Field
{
    use Concerns\CanBeAutocompleted;
    use Concerns\CanBeObfuscated;
    use Concerns\HasPlaceholder;

    protected string $snippet = 'webform/honeypot';

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function create(string $name): static
    {
        return new static($name);
    }
}
