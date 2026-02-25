<?php

use Kirby\Content\Field;
use Kirby\Filesystem\Mime;
use Kirby\Toolkit\A;
use Kirby\Toolkit\V;
use Webform\Http\UploadedFile;

return [

    /**
     * Validate the date is after a given date.
     */
    'after' => function (?string $value, ?string $other): bool {
        return V::date($value, '>', $other);
    },

    /**
     * Validate the date is equal or after a given date.
     */
    'afterOrEqual' => function (?string $value, ?string $other): bool {
        return V::date($value, '>=', $other);
    },

    /**
     * Validate the date is before a given date.
     */
    'before' => function (?string $value, ?string $other): bool {
        return V::date($value, '<', $other);
    },

    /**
     * Validate the date is before or equal a given date.
     */
    'beforeOrEqual' => function (?string $value, ?string $other): bool {
        return V::date($value, '<=', $other);
    },

    /**
     * Validate that the value is an array.
     */
    'array' => function (mixed $value, ?array $keys = null): bool {
        if (! is_array($value)) {
            return false;
        }

        if (empty($keys)) {
            return true;
        }

        return empty(array_diff_key(array_fill_keys($keys, true), $value));
    },

    /**
     * Validate that the value is a list.
     */
    'list' => function (mixed $value): bool {
        return is_array($value) && array_is_list($value);
    },

    /**
     * Validate the existence of a value in a collection.
     */
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

    /**
     * Validate that the given file is valid.
     */
    'file' => function (mixed $file, bool $required = false): bool {
        if (! ($file instanceof UploadedFile) || ! $file->isReadable()) {
            return ! $required;
        }

        return $file->isValid();
    },

    /**
     * Validate that the given file is a document.
     */
    'document' => function (mixed $file): bool {
        return V::mimeType($file, ['document/*']);
    },

    /**
     * Validate that the given file is an image file.
     */
    'image' => function (mixed $file): bool {
        return V::mimeType($file, ['image/*']);
    },

    /**
     * Validate that the given file is a video file.
     */
    'video' => function (mixed $file): bool {
        return V::mimeType($file, ['video/*']);
    },

    /**
     * Validate the MIME type of a file.
     */
    'mimeType' => function (mixed $file, string|array $allowedMimeTypes): bool {
        if (! ($file instanceof UploadedFile)) {
            return true;
        }

        $allowedMimeTypes = A::wrap($allowedMimeTypes);

        if (! $allowedMimeTypes) {
            return true;
        }

        $mimeType = $file->getMimeType();

        if (! $mimeType) {
            return false;
        }

        foreach ($allowedMimeTypes as $allowedMimeType) {
            if (Mime::matches($mimeType, $allowedMimeType)) {
                return true;
            }
        }

        return false;
    },

    /**
     * Validate that the size of a file is larger or equal to a given size.
     */
    'minFileSize' => function (mixed $file, int $size): bool {
        if (! ($file instanceof UploadedFile)) {
            return true;
        }

        return $file->getSize() >= $size;
    },

    /**
     * Validate that the size of a file is smaller or equal to a given size.
     */
    'maxFileSize' => function (mixed $file, int $size): bool {
        if (! ($file instanceof UploadedFile)) {
            return true;
        }

        return $file->getSize() <= $size;
    },

];
