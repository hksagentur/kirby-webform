<?php

namespace Webform\Form\Components\Concerns;

use Kirby\Cms\R;
use Kirby\Cms\S;
use Kirby\Toolkit\A;
use Kirby\Toolkit\V;

trait HasValue
{
    protected mixed $defaultValue = null;

    public function isFilled(): bool
    {
        return ! $this->isNotFilled();
    }

    public function isNotFilled(): bool
    {
        return in_array($this->getValue(), [[], '', null], true);
    }

    public function hasBeenFilled(): bool
    {
        return ! $this->hasNotBeenFilled();
    }

    public function hasNotBeenFilled(): bool
    {
        return in_array($this->getOldValue(), [[], '', null], true);
    }

    public function getValue(): mixed
    {
        $key = $this->getName();

        if (V::empty($key)) {
            return null;
        }

        $value = $this->requestData($key);

        if (is_array($value)) {
            return $value;
        }

        if (V::empty($value)) {
            return null;
        }

        return $value;
    }

    public function getOldValue(): mixed
    {
        $key = $this->getName();

        if (V::empty($key)) {
            return null;
        }

        $value = $this->sessionData($key);

        if (V::empty($value)) {
            return null;
        }

        return $value;
    }

    public function getDefaultValue(): mixed
    {
        return $this->evaluate($this->defaultValue);
    }

    public function default(mixed $value): static
    {
        $this->defaultValue = $value;

        return $this;
    }

    protected function requestData(?string $key = null): mixed
    {
        $data = R::data();

        if (is_null($key)) {
            return $data;
        }

        return A::get($data, $key, []);
    }

    protected function sessionData(?string $key = null): mixed
    {
        $data = S::get('webform.form.input', []);

        if (is_null($key)) {
            return $data;
        }

        return A::get($data, $key, []);
    }
}
