<?php

use Kirby\Cms\Block;

return [
    /**
     * Find a content block by a unique identifier.
     */
    'block' => function (string $id, string $field = 'blocks'): ?Block {
        try {
            return $this->content()->get($field)->toBlocks()->findByKey($id);
        } catch (InvalidArgumentException) {
            return null;
        }
    },
];
