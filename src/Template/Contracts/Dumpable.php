<?php

namespace Webform\Template\Contracts;

interface Dumpable
{
    public function dd(): never;

    public function dump(): static;
}
