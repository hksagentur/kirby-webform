<?php

namespace Webform\Form\Components\Contracts;

interface CanBeLengthConstrained
{
    public function getLength(): ?int;
    public function getMinLength(): ?int;
    public function getMaxLength(): ?int;
}
