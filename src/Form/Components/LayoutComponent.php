<?php

namespace Webform\Form\Components;

abstract class LayoutComponent extends Component implements Contracts\HasChildren
{
    use Concerns\HasChildren;

    public function getSnippetContext(): array
    {
        return array_merge(parent::getSnippetContext(), [
            'children' => $this->getChildren()->visible(),
        ]);
    }
}
