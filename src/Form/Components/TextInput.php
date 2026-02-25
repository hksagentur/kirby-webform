<?php

namespace Webform\Form\Components;

use Closure;

class TextInput extends Field implements Contracts\CanBeLengthConstrained
{
    use Concerns\CanBeAutocompleted;
    use Concerns\CanBeLengthConstrained;
    use Concerns\CanBeReadOnly;
    use Concerns\HasDatalist;
    use Concerns\HasInputMode;
    use Concerns\HasPlaceholder;
    use Concerns\HasStep;

    protected string $snippet = 'webform/text-input';

    protected string|Closure|null $type = null;

    protected int|float|string|Closure|null $minValue = null;
    protected int|float|string|Closure|null $maxValue = null;

    protected bool|Closure $isEmail = false;
    protected bool|Closure $isNumber = false;
    protected bool|Closure $isPassword = false;
    protected bool|Closure $isTel = false;
    protected bool|Closure $isUrl = false;

    public function isEmail(): bool
    {
        return $this->evaluate($this->isEmail);
    }

    public function isNumber(): bool
    {
        return $this->evaluate($this->isNumber);
    }

    public function isPassword(): bool
    {
        return $this->evaluate($this->isPassword);
    }

    public function isTel(): bool
    {
        return $this->evaluate($this->isTel);
    }

    public function isUrl(): bool
    {
        return $this->evaluate($this->isUrl);
    }

    public function getType(): string
    {
        if ($type = $this->evaluate($this->type)) {
            return $type;
        } elseif ($this->isEmail()) {
            return 'email';
        } elseif ($this->isNumber()) {
            return 'number';
        } elseif ($this->isPassword()) {
            return 'password';
        } elseif ($this->isTel()) {
            return 'tel';
        } elseif ($this->isUrl()) {
            return 'url';
        } else {
            return 'text';
        }
    }

    public function getMinValue(): int|float|string|null
    {
        return $this->evaluate($this->minValue);
    }

    public function getMaxValue(): int|float|string|null
    {
        return $this->evaluate($this->maxValue);
    }

    public function type(string|Closure|null $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function number(bool|Closure $condition = true): static
    {
        $this->isNumber = $condition;

        $this->rule('num', $condition);

        $this->inputMode(static fn (): ?string => $condition ? 'decimal' : null);
        $this->step(static fn (): ?string => $condition ? 'any' : null);

        return $this;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     * @todo {@link https://github.com/vimeo/psalm/issues/8673}
     */
    public function email(bool|Closure $condition = true): static
    {
        $this->isEmail = $condition;

        $this->rule('email', $condition);

        return $this;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     * @todo {@link https://github.com/vimeo/psalm/issues/8673}
     */
    public function tel(bool|Closure $condition = true): static
    {
        $this->isTel = $condition;

        $this->rule('tel', $condition);

        return $this;
    }

    /**
     * @psalm-suppress MethodSignatureMismatch
     * @todo {@link https://github.com/vimeo/psalm/issues/8673}
     */
    public function url(bool|Closure $condition = true): static
    {
        $this->isUrl = $condition;

        $this->rule('url', $condition);

        return $this;
    }

    public function password(bool|Closure $condition = true): static
    {
        $this->isPassword = $condition;

        return $this;
    }

    public function minValue(int|float|string|Closure|null $value): static
    {
        $this->minValue = $value;

        return $this;
    }

    public function maxValue(int|float|string|Closure|null $value): static
    {
        $this->maxValue = $value;

        return $this;
    }
}
