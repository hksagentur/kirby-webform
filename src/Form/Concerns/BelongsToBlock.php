<?php

namespace Webform\Form\Concerns;

use Kirby\Cms\Block;

trait BelongsToBlock
{
    protected ?Block $block = null;

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function getBlockId(): ?string
    {
        return $this->block?->id();
    }

    public function block(Block $block): static
    {
        $this->block = $block;

        return $this;
    }
}
