<?php

namespace Webform\Form\Components\Concerns;

use Kirby\Cms\R;
use Kirby\Cms\S;
use Kirby\Toolkit\A;
use Kirby\Toolkit\V;

trait HasValue
{
    protected mixed $defaultValue = null;

    public function getDefaultValue(): mixed
    {
        return $this->evaluate($this->defaultValue);
    }

    public function getValue(): mixed
    {
        $name = $this->getName();

        if (V::empty($name)) {
            return null;
        }

        $value = R::get($name);

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
        $name = $this->getName();

        if (V::empty($name)) {
            return null;
        }

        $value = A::get(S::get('webform.form.input', []), $name);

        if (V::empty($value)) {
            return null;
        }

        return $value;
    }

    public function default(mixed $value): static
    {
        $this->defaultValue = $value;

        return $this;
    }
}
