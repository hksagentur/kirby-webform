<?php

namespace Webform\Template;

use ArrayIterator;
use IteratorAggregate;
use Webform\Template\Concerns\CanBeInspected;
use Webform\Template\Contracts\Dumpable;

/**
 * @implements IteratorAggregate<string, mixed>
 */
abstract readonly class ViewModel implements Dumpable, IteratorAggregate
{
    use CanBeInspected;

    abstract public function toArray(): array;

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }
}
