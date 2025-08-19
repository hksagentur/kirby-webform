<?php

namespace Webform\Support;

use Stringable;

abstract class ViewComponent implements Stringable
{
    use Concerns\CanBeRendered;
    use Concerns\Conditionable;
    use Concerns\EvaluatesClosures;
}
