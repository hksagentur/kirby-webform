<?php

namespace Webform\Form\Components;

use Kirby\Cms\R;

class Honeypot extends TextInput implements Contracts\ProvidesChallenge
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

    public function verify(): bool
    {
        return in_array(R::get($this->getName()), ['', [], null], true);
    }
}
