<?php

namespace Webform\Form\Components;

use Kirby\Toolkit\Str;

class Field extends Component implements Contracts\CanBeRequired, Contracts\HasValidationRules
{
    use Concerns\CanBeDisabled;
    use Concerns\CanBeRequired;
    use Concerns\CanBeValidated;
    use Concerns\HasHelp;
    use Concerns\HasHint;
    use Concerns\HasLabel;
    use Concerns\HasName;
    use Concerns\HasValue;

    public function __construct(string $name)
    {
        $this->name($name);
    }

    public static function create(string $name): static
    {
        return new static($name);
    }

    public function getId(): string
    {
        return $this->evaluate($this->id) ?? Str::kebab($this->getName());
    }

    public function getKey(): string
    {
        return $this->evaluate($this->key) ?? Str::kebab($this->getName());
    }

    public function getLabel(): ?string
    {
        return $this->evaluate($this->label) ?? Str::ucfirst($this->getName());
    }
}
