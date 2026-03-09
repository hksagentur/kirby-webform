<?php

namespace Webform\Form\Components;

use Webform\Form\Concerns\HasChildren;

class Container extends Component
{
    use HasChildren;

    protected string $snippet = 'webform/container';

    public static function create(): static
    {
        return new static();
    }

    public function getEvaluationContext(): array
    {
        return array_merge([
            'container' => $this,
        ], parent::getEvaluationContext());
    }

    public function getSnippetContext(): array
    {
        return array_merge([
            'container' => $this,
            'children' => $this->getChildren()->visible(),
        ], parent::getSnippetContext());
    }
}
