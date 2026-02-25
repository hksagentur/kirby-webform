<?php

namespace Webform\Template;

use Stringable;
use Webform\Template\Concerns\CanBeInspected;
use Webform\Template\Concerns\CanBeRendered;
use Webform\Template\Contracts\Dumpable;
use Webform\Template\Contracts\Htmlable;

abstract class ViewComponent implements Dumpable, Htmlable, Stringable
{
    use CanBeInspected;
    use CanBeRendered;

    abstract public function evaluate(mixed $value): mixed;
}
