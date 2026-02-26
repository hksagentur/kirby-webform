<?php

namespace Webform\Toolkit;

interface Jsonable
{
    public function toJson(int $options = 0): string;
}
