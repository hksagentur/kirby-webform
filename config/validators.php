<?php

use Kirby\Content\Field;
use Kirby\Toolkit\V;

return [
    'exists' => function (string $value, string $collection, string $field = 'slug', bool $strict = false): bool {
        $values = [];
        foreach (collection($collection) as $item) {
            $value = collection($collection)->getAttribute($item, $field);

            if ($value instanceof Field) {
                $values[] = $value->value();
            } else {
                $values[] = $value;
            }
        }

        return V::in($value, $values, $strict);
    },
];
