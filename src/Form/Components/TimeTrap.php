<?php

namespace Webform\Form\Components;

use Closure;
use Kirby\Toolkit\Date;

class TimeTrap extends Hidden
{
    use Concerns\CanBeEncrypted;

    protected string $snippet = 'webform/time-trap';

    protected int|Closure $minDelay = 0;

    public function __construct(string $name)
    {
        $this->name($name);
        $this->default(Date::now()->getTimestamp());
    }

    public function getMinDelay(): int
    {
        return $this->evaluate($this->minDelay);
    }

    public function minDelay(int|Closure $seconds): static
    {
        $this->minDelay = $seconds;

        return $this;
    }
}
