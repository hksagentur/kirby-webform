<?php

namespace Webform\Template\Concerns;

trait CanBeInspected
{
    public function dd(): never
    {
        dump($this);
        exit(1);
    }

    public function dump(): static
    {
        dump($this);

        return $this;
    }

    public function __debugInfo(): array
    {
        return [
            'snippet' => $this->getSnippet(),
            'data' => [
                ...$this->resolveDefaultSnippetData(),
                ...$this->getSnippetData(),
            ],
        ];
    }
}
