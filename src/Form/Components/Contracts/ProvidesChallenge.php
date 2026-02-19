<?php

namespace Webform\Form\Components\Contracts;

interface ProvidesChallenge
{
    public function verify(): bool;
}
