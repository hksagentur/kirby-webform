<?php

namespace Webform\Form\Components;

use Webform\Template\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\BelongsToContainer;
    use Concerns\CanAllowHtml;
    use Concerns\CanBeHidden;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;

    public const ALIAS = 'component';

    public function getEvaluationContext(): array
    {
        return array_merge([
            'container' => $this->getContainer(),
            'form' => $this->getForm(),
            'block' => $this->getForm()?->getContext()->block(),
            'page' => $this->getForm()?->getContext()->page(),
        ], parent::getEvaluationContext());
    }

    public function getSnippetContext(): array
    {
        return array_merge([
            'container' => $this->getContainer(),
            'form' => $this->getForm(),
            'block' => $this->getForm()?->getContext()->block(),
            'page' => $this->getForm()?->getContext()->page(),
        ], parent::getSnippetContext());
    }
}
