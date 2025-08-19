<?php

namespace Webform\Form\Components;

use Closure;

class DateTimePicker extends Field
{
    use Concerns\CanBeAutocompleted;
    use Concerns\CanBeReadonly;
    use Concerns\HasDatalist;
    use Concerns\HasPlaceholder;
    use Concerns\HasStep;

    protected string $snippet = 'webform/datetime';

    protected bool|Closure $hasDate = true;
    protected bool|Closure $hasTime = true;

    protected string|Closure|null $minDate = null;
    protected string|Closure|null $maxDate = null;

    public function getType(): string
    {
        if (! $this->hasDate()) {
            return 'time';
        }

        if (! $this->hasTime()) {
            return 'date';
        }

        return 'datetime-local';
    }

    public function hasDate(): bool
    {
        return (bool) $this->evaluate($this->hasDate);
    }

    public function hasTime(): bool
    {
        return (bool) $this->evaluate($this->hasTime);
    }

    public function getMinDate(): ?string
    {
        return $this->evaluate($this->minDate);
    }

    public function getMaxDate(): ?string
    {
        return $this->evaluate($this->maxDate);
    }

    public function minDate(string|Closure|null $value): static
    {
        $this->minDate = $value;

        $this->afterOrEqual($value);

        return $this;
    }

    public function maxDate(string|Closure|null $value): static
    {
        $this->maxDate = $value;

        $this->beforeOrEqual($value);

        return $this;
    }
}
