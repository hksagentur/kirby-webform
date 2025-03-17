<?php

use Kirby\Content\Field;

return [
    'toInputOptions' => function (string $field = 'title', string $key = 'slug'): array {
        /** @var \Kirby\Toolkit\Collection $this */
        $options = [];

        foreach ($this->data as $item) {
            $value = $this->getAttribute($item, $field);

            if ($value instanceof Field) {
                $value = $value->value();
            }

            $id = $this->getAttribute($item, $key);

            if ($id instanceof Field) {
                $id = $id->value();
            }

            $options[$id] = $value;
        }

        return $options;
    },
];
