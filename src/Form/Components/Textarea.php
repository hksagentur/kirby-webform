<?php

namespace Webform\Form\Components;

use Closure;

class Textarea extends Field
{
    use Concerns\CanBeLengthConstrained;
    use Concerns\CanBeReadOnly;
    use Concerns\HasPlaceholder;

    protected string $snippet = 'webform/textarea';

    protected int|Closure|null $cols = null;
    protected int|Closure|null $rows = null;

    public function getCols(): ?int
    {
        return $this->evaluate($this->cols);
    }

    public function getRows(): ?int
    {
        return $this->evaluate($this->rows);
    }

    public function cols(int|Closure|null $cols): static
    {
        $this->cols = $cols;

        return $this;
    }

    public function rows(int|Closure|null $rows): static
    {
        $this->rows = $rows;

        return $this;
    }
}
