<?php

namespace Webform\Support\Concerns;

use Closure;
use Exception;
use Kirby\Cms\App;
use Webform\Form\Manager;

trait CanBeRendered
{
    protected string|Closure|null $defaultSnippet = null;
    protected string $snippet;

    protected array $snippetData = [];

    public function getDefaultSnippet(): ?string
    {
        return $this->evaluate($this->defaultSnippet);
    }

    public function defaultSnippet(string|Closure|null $snippet): static
    {
        $this->defaultSnippet = $snippet;

        return $this;
    }

    public function getSnippet(): string
    {
        if (isset($this->snippet)) {
            return $this->snippet;
        }

        if ($defaultSnippet = $this->getDefaultSnippet()) {
            return $defaultSnippet;
        }

        throw new Exception(sprintf('Component [%s] does not provide a snippet path.', static::class));
    }

    public function snippet(?string $snippet, array $data = []): static
    {
        if (is_null($snippet)) {
            return $this;
        }

        $this->snippet = $snippet;

        if (empty($data)) {
            return $this;
        }

        return $this->snippetData($data);
    }

    public function getSnippetData(): array
    {
        return $this->snippetData;
    }

    public function snippetData(array $data): static
    {
        $this->snippetData = [
            ...$this->snippetData,
            ...$data,
        ];

        return $this;
    }

    public function render(array $data = []): string
    {
        return App::instance()->snippet(
            name: $this->getSnippet(),
            data: [
                'status' => Manager::instance()->status(),
                'errors' => Manager::instance()->errors(),
                ...$this->resolveDefaultSnippetData(),
                ...$this->getSnippetData(),
                ...$data,
            ],
            return: true,
        );
    }

    public function toString(): string
    {
        return $this->render();
    }

    public function toHtml(): string
    {
        return $this->render();
    }

    public function __toString(): string
    {
        return $this->render();
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [];
    }
}
