<?php

namespace Webform\Form\Components;

use Webform\Support\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\CanAllowHtml;
    use Concerns\HasChildren;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;

    protected function resolveDefaultEvaluationData(): array
    {
        return [
            'component' => $this,
            'form' => $this->getForm(),
            'model' => $this->getForm()?->getModel(),
            'block' => $this->getForm()?->getBlock(),
        ];
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [
            'component' => $this,
            'form' => $this->getForm(),
            'model' => $this->getForm()?->getModel(),
            'block' => $this->getForm()?->getBlock(),
            'status' => $this->getForm()?->getStatusMessage(),
            'errors' => $this->getForm()?->getErrorMessages(),
            'children' => $this->getChildren(),
        ];
    }
}
