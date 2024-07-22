<?php

namespace Webform\Cms;

use Kirby\Cms\Collection;

class FormConfigCollection extends Collection
{
    public static function fromDirectory(string $directory): static
    {
        $collection = new static();

        foreach (glob($directory . '/*.php') as $file) {
            $collection->add(new FormConfig(
                path: basename($file, '.php'),
                root: $directory,
            ));
        }

        return $collection;
    }
}
