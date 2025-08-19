<?php

namespace Webform\Form\Components\Contracts;

interface HasValidationRules
{
    public function getValidationRules(): array;
    public function getValidationMessages(): array;
}
