<?php

namespace Webform\Form\Components;

use Webform\Form\Concerns as Foundation;
use Webform\Template\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\CanAllowHtml;
    use Concerns\HasChildren;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;
    use Foundation\CanBeTraversed;
    use Foundation\EvaluatesClosures;

    public function getPropertyValue(string $property, mixed $default = null): mixed
    {
        return match ($property) {
            'id' => $this->getId(),
            'key' => $this->getKey(),
            default => $default,
        };
    }

    public function getEvaluationContext(): array
    {
        return [
            'component' => $this,
            'form' => $this->getForm(),
            'model' => $this->getForm()?->getModel(),
            'block' => $this->getForm()?->getBlock(),
        ];
    }

    public function getSnippetContext(): array
    {
        return [
            'component' => $this,
            'form' => $this->getForm(),
            'model' => $this->getForm()?->getModel(),
            'block' => $this->getForm()?->getBlock(),
        ];
    }
}
