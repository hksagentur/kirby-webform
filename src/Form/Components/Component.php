<?php

namespace Webform\Form\Components;

use Webform\Support\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\CanAllowHtml;
    use Concerns\HasChildComponents;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;

    protected function resolveDefaultEvaluationData(): array
    {
        return [
            ...$this->getForm()?->resolveDefaultEvaluationData() ?? [],
            'component' => $this,
        ];
    }

    protected function resolveDefaultSnippetData(): array
    {
        return [
            ...$this->getForm()?->resolveDefaultSnippetData() ?? [],
            'component' => $this,
            'childComponents' => $this->getChildComponents(depth: 1),
        ];
    }
}
