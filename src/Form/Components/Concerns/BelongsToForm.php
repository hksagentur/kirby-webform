<?php

namespace Webform\Form\Components\Concerns;

use Webform\Form\Form;

trait BelongsToForm
{
    protected ?Form $form = null;

    public function getForm(): ?Form
    {
        return $this->form;
    }

    public function form(?Form $form): static
    {
        $this->form = $form;

        return $this;
    }
}
