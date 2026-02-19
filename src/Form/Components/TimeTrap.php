<?php

namespace Webform\Form\Components;

use Closure;
use Kirby\Cms\R;
use Kirby\Toolkit\Date;

class TimeTrap extends Hidden implements Contracts\ProvidesChallenge
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

    public function verify(): bool
    {
        $timestamp = R::get($this->getName());

        if (! $timestamp) {
            return false;
        }

        if ($this->shouldEncrypt()) {
            $timestamp = $this->encrypter()->decrypt(base64_decode($timestamp, strict: true));
        }

        if (! $timestamp) {
            return false;
        }

        $minDelay = $this->getMinDelay();

        if ($timestamp > (time() - $minDelay)) {
            return false;
        }

        return true;
    }
}
