<?php

namespace Webform\Form\Components;

class Honeypot extends TextInput
{
    use Concerns\CanBeObfuscated;

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
