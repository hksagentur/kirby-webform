<?php

namespace Webform\Template;

use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Webform\Toolkit\Arrayable;
use Webform\Toolkit\Jsonable;

/**
 * @implements Arrayable<string, mixed>
 * @implements IteratorAggregate<string, mixed>
 */
abstract readonly class ViewModel implements Arrayable, IteratorAggregate, Jsonable, JsonSerializable
{
    abstract public function toArray(): array;

    public function toJson(int $options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->toArray());
    }
}
