<?php

namespace Webform\Form\Components;

use Webform\Form\Concerns\EvaluatesClosures;
use Webform\Template\ViewComponent;

abstract class Component extends ViewComponent
{
    use Concerns\BelongsToForm;
    use Concerns\CanAllowHtml;
    use Concerns\HasExtraAttributes;
    use Concerns\HasId;
    use Concerns\HasKey;
    use EvaluatesClosures;

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

    public function getPropertyValue(string $name, mixed $default = null): mixed
    {
        return match ($name) {
            'id' => $this->getId(),
            'key' => $this->getKey(),
            default => $default,
        };
    }
}
