<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\ModelWithContent;

trait BelongsToModel
{
    protected ?ModelWithContent $model = null;

    public function getModel(): ?ModelWithContent
    {
        return $this->model;
    }

    public function getModelId(): ?string
    {
        return $this->model?->id();
    }

    public function model(ModelWithContent $model): static
    {
        $this->model = $model;

        return $this;
    }
}
