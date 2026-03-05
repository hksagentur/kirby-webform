<?php

namespace Webform\Form\Components;

use Closure;
use Kirby\Toolkit\Str;

class Button extends Component
{
    use Concerns\CanBeDisabled;
    use Concerns\HasAction;
    use Concerns\HasExtraAttributes;
    use Concerns\HasLabel;
    use Concerns\HasName;
    use Concerns\HasValue;

    protected string $snippet = 'webform/button';

    protected string|Closure $type = 'submit';

    public function __construct(?string $name = null)
    {
        $this->name($name);
    }

    public static function create(?string $name = null): static
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

    public function getType(): string
    {
        return $this->evaluate($this->type);
    }

    public function type(string|Closure $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function trigger(array $parameters = []): mixed
    {
        return $this->evaluate($this->getAction(), $parameters);
    }
}
