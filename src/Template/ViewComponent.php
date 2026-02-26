<?php

namespace Webform\Template;

use Stringable;
use Webform\Template\Concerns\CanBeRendered;
use Webform\Toolkit\Htmlable;

abstract class ViewComponent implements Htmlable, Stringable
{
    use CanBeRendered;

    abstract public function evaluate(mixed $value): mixed;
}
