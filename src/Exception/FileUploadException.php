<?php

namespace Webform\Exception;

use Kirby\Exception\Exception;
use Webform\Form\Components\FileUpload;
use Webform\Form\MessageBag;
use Webform\Form\UploadedFile;

class FileUploadException extends Exception
{
    protected static string $defaultKey = 'hksagentur.webform.upload.default';
    protected static string $defaultFallback = 'Invalid file upload [{ file }].';

    protected static int $defaultHttpCode = 400;

    protected static array $defaultData = ['field' => null, 'file' => null];

    protected ?FileUpload $uploadField = null;
    protected ?UploadedFile $uploadedFile = null;

    public function __construct(string|array $arguments = [])
    {
        $this->uploadedFile = $arguments['file'] ?? null;
        $this->uploadField = $arguments['field'] ?? null;

        $arguments += [
            'key' => match ($this->uploadedFile?->getError()) {
                UPLOAD_ERR_INI_SIZE => 'hksagentur.webform.upload.iniSize',
                UPLOAD_ERR_FORM_SIZE => 'hksagentur.webform.upload.formSize',
                UPLOAD_ERR_PARTIAL => 'hksagentur.webform.upload.partial',
                UPLOAD_ERR_NO_FILE => 'hksagentur.webform.upload.noFile',
                UPLOAD_ERR_NO_TMP_DIR => 'hksagentur.webform.upload.noTmpDir',
                UPLOAD_ERR_CANT_WRITE => 'hksagentur.webform.upload.cantWrite',
                UPLOAD_ERR_EXTENSION => 'hksagentur.webform.upload.extension',
                default => 'hksagentur.webform.upload.default',
            },
            'data' => [
                'field' => $this->uploadField?->getName(),
                'file' => $this->uploadedFile?->getName(),
                'limit' => UploadedFile::getMaxFileSize(),
            ],
        ];

        parent::__construct($arguments);
    }

    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    public function getUploadField(): ?FileUpload
    {
        return $this->uploadField;
    }

    public function getErrors(): MessageBag
    {
        return new MessageBag([
            $this->uploadField?->getName() ?? 'upload' => $this->getMessage(),
        ]);
    }
}
