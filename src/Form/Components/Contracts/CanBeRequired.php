<?php

namespace Webform\Form\Components\Contracts;

interface CanBeRequired
{
    public function isRequired(): bool;
}
