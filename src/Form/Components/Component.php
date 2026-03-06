<?php

namespace Webform\Form\Components;

use Webform\Form\Concerns\EvaluatesClosures;
use Webform\Template\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\CanAllowHtml;
    use Concerns\CanBeHidden;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;
    use EvaluatesClosures;

    public function getEvaluationContext(): array
    {
        $context = $this->getForm()?->getEvaluationContext();

        if (! $context) {
            return ['component' => $this];
        }

        return array_merge($context, ['component' => $this]);
    }

    public function getSnippetContext(): array
    {
        $context = $this->getForm()?->getSnippetContext();

        if (! $context) {
            return ['component' => $this];
        }

        return array_merge($context, ['component' => $this]);
    }
}
