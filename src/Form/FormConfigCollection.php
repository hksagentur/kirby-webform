<?php

namespace Webform\Form;

use Kirby\Toolkit\Collection;

class FormConfigCollection extends Collection
{
    public static function fromDirectory(string $directory): static
    {
        $collection = new static();

        foreach (glob($directory . '/*.php') as $file) {
            $path = basename($file, '.php');

            $collection->set(
                $path,
                new FormConfig($path, $directory)
            );
        }

        return $collection;
    }
}
