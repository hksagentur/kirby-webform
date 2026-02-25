<?php

namespace Webform\Template\Contracts;

interface Htmlable
{
    public function toHtml(): string;
}
