<?php

namespace Webform\Form\Concerns;

use Kirby\Filesystem\File;
use Webform\Exception\FileUploadException;
use Webform\Form\Components\FileUpload;

trait HandlesFileUploads
{
    /** @return array<string, File[]> */
    public function saveUploadedFiles(): array
    {
        $files = [];

        foreach ($this->getFieldsByType(FileUpload::class) as $field) {
            try {
                $files[$field->getName()] = $field->saveUploadedFiles();
            } catch (FileUploadException $exception) {
                // Append field information to the exception.
                throw new FileUploadException([
                    'field' => $exception->getUploadField(),
                    'file' => $exception->getUploadedFile(),
                    'previous' => $exception,
                ]);
            }
        }

        return $files;
    }
}
