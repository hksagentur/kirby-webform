<?php

namespace Webform\Form\Concerns;

use Kirby\Filesystem\File;
use Webform\Exception\FileUploadException;
use Webform\Form\Components\FileUpload;

trait HandlesFileUploads
{
    /** @return array<string, File[]> */
    protected ?array $uploadedFiles = null;

    /** @return array<string, File[]> */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles ??= $this->saveUploadedFiles();
    }

    /** @return array<string, File[]> */
    public function saveUploadedFiles(): array
    {
        $files = [];

        foreach ($this->getChildren()->getIndex()->whereInstanceOf(FileUpload::class) as $field) {
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
