<?php

namespace Webform\Http\Exception;

use Kirby\Exception\Exception;
use Webform\Http\UploadedFile;
use Webform\Validation\Messages;

class FileUploadException extends Exception
{
    protected ?UploadedFile $uploadedFile = null;
    protected ?Messages $uploadErrors = null;

    public function __construct(string|array $arguments = [])
    {
        if (is_string($arguments)) {
            $arguments = ['key' => $arguments];
        }

        $this->uploadErrors = new Messages();

        /** @var ?UploadedFile $file */
        $file = $arguments['file'] ?? null;

        /** @var array|Messages $errors*/
        $errors = $arguments['errors'] ?? [];

        $arguments += [
            'key' => match ($file?->getError()) {
                UPLOAD_ERR_INI_SIZE => 'hksagentur.webform.upload.iniSize',
                UPLOAD_ERR_FORM_SIZE => 'hksagentur.webform.upload.formSize',
                UPLOAD_ERR_PARTIAL => 'hksagentur.webform.upload.partial',
                UPLOAD_ERR_NO_FILE => 'hksagentur.webform.upload.noFile',
                UPLOAD_ERR_NO_TMP_DIR => 'hksagentur.webform.upload.noTmpDir',
                UPLOAD_ERR_CANT_WRITE => 'hksagentur.webform.upload.cantWrite',
                UPLOAD_ERR_EXTENSION => 'hksagentur.webform.upload.extension',
                default => 'hksagentur.webform.upload.default',
            },
            'fallback' => 'Invalid file upload [{ file }].',
            'httpCode' => 400,
            'data' => [
                'file' => $file?->getName(),
                'limit' => UploadedFile::getMaxFileSize(),
            ],
        ];

        $this->withUploadedFile($file);
        $this->withUploadErrors($errors);

        parent::__construct($arguments);
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function withUploadedFile(?UploadedFile $file): static
    {
        $this->uploadedFile = $file;

        return $this;
    }

    public function getUploadErrors(): Messages
    {
        return $this->uploadErrors->isNotEmpty()
            ? $this->uploadErrors
            : Messages::fromString($this->getMessage());
    }

    public function withUploadErrors(array|Messages $errors): static
    {
        $this->uploadErrors->merge($errors);

        return $this;
    }
}
